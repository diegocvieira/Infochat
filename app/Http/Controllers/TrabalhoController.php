<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trabalho;
use Auth;
use App\Area;
use App\Categoria;
use App\Subcategoria;
use App\Cidade;

class TrabalhoController extends Controller
{
    public function getConfig()
    {
        $trabalho = Trabalho::where('user_id', Auth::guard('web')->user()->id)
                            ->withoutGlobalScope('ativo')
                            ->withoutGlobalScope('cidade')
                            ->first();

        $tipos = [
            '1' => 'Profissional',
            '2' => 'Estabelecimento'
        ];

        $tNow = strtotime("00:00");
        $horarios = [];
        while($tNow <= strtotime("23:30")) {
            $horarios[date("H:i", $tNow)] = date("H:i", $tNow);
            $tNow = strtotime('+30 minutes', $tNow);
        }

        $dias_semana = [
            '6' => 'Domingo',
            '0' => 'Segunda',
            '1' => 'Terça',
            '2' => 'Quarta',
            '3' => 'Quinta',
            '4' => 'Sexta',
            '5' => 'Sábado'
        ];

        if(isset($trabalho)) {
            $areas = Area::where('tipo', $trabalho->tipo)->pluck('titulo', 'id');

            $categorias = Categoria::where('area_id', $trabalho->area_id)->get();
        }

        return response()->json([
            'body' => view('admin.trabalho-config', compact('categorias', 'areas', 'trabalho', 'tipos', 'horarios', 'dias_semana'))->render()
        ]);
    }

    public function setConfig(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $slug = str_slug($request->slug, '-');

        // Validar o slug
        $slug_validate = Trabalho::where('slug', $slug)
                                ->where('user_id', '!=', $user_id)
                                ->withoutGlobalScope('ativo')
                                ->withoutGlobalScope('cidade')
                                ->count();

        if($slug_validate == 0 && $request->nome && $slug && $request->area_id && $request->tipo) {
            // Verificar se o trabalho ja existe
            $t = Trabalho::where('user_id', $user_id)
                        ->withoutGlobalScope('ativo')
                        ->withoutGlobalScope('cidade')
                        ->first();

            // Escolher entre create e update
            if(isset($t)) {
                $msg = 'Informações alteradas com sucesso!';
                $trabalho = $t;
            } else {
                $msg = 'Perfil criado com sucesso!';
                $trabalho = new Trabalho;
            }

            // Buscar a cidade no banco
            $cidade = Cidade::whereHas('estado', function($q) use($request) {
                $q->where('letter', $request->estado);
            })->where('title', 'LIKE', '%' . $request->cidade . '%')->select('id')->first();
        	$cidade_id = $cidade ? $cidade->id : null;

            $trabalho->cidade_id = $cidade_id;
            $trabalho->user_id = $user_id;
            $trabalho->slug = $slug;
            $trabalho->tipo = $request->tipo;
            $trabalho->nome = $request->nome;
            $trabalho->descricao = $request->descricao;
            $trabalho->logradouro = $request->logradouro;
            $trabalho->numero = $request->numero;
            $trabalho->bairro = $request->bairro;
            $trabalho->complemento = $request->complemento;
            $trabalho->cpf_cnpj = $request->cpf_cnpj;
            $trabalho->area_id = $request->area_id;
            $trabalho->cep = $request->cep;
            $trabalho->email = $request->email;

            if(!empty($request->img)) {
                if(isset($t) && $t->imagem) {
                    unlink('uploads/perfil/' . $t->imagem);
                }

                // Move  a imagem para a pasta
                $file = $request->img;
                $fileName = date('YmdHis') . microtime(true) . rand(111111111, 999999999) . '.' . $file->getClientOriginalExtension(); // Renomear
                $file->move('uploads/perfil', $fileName); // Mover para a pasta

                $trabalho->imagem = $fileName;
            }

            $trabalho->save();

            // Telefones
            $trabalho->telefones()->delete();
            if(isset($request->fone)) {
                foreach($request->fone as $fone) {
                    if(!empty($fone)) {
                        $trabalho->telefones()->create(['fone' => $fone]);
                    }
                }
            }

            // Redes sociais
            $trabalho->redes()->delete();
            if(isset($request->social)) {
                foreach($request->social as $social) {
                    if(!empty($social)) {
                        $trabalho->redes()->create(['url' => $social]);
                    }
                }
            }

            // Tags
            $trabalho->tags()->delete();
            if(isset($request->tag)) {
                foreach($request->tag as $tag) {
                    $trabalho->tags()->create(['tag' => $tag]);
                }
            }

            // Horarios de atendimento
            $trabalho->horarios()->delete();
            $horarios = array_map(function($d, $dm, $at, $dt, $an) {
                return array('dia' => $d, 'de_manha' => $dm, 'ate_tarde' => $at, 'de_tarde' => $dt, 'ate_noite' => $an);
            }, $request->dia, $request->de_manha, $request->ate_tarde, $request->de_tarde, $request->ate_noite);
            foreach($horarios as $horario) {
                if($horario['dia'] && ($horario['de_manha'] || $horario['ate_tarde'] && $horario['de_tarde'] || $horario['ate_noite'])) {
                    $trabalho->horarios()->create($horario);
                }
            }
        } else {
            $msg = 'Esta url já está em uso. Escolha outro e tente novamente.';
        }

        return json_encode(['msg' => $msg]);
    }

    public function setStatus(Request $request)
    {
        $trabalho = Trabalho::where('user_id', Auth::guard('web')->user()->id)
                            ->withoutGlobalScope('ativo')
                            ->withoutGlobalScope('cidade')
                            ->first();

        if(count($trabalho) > 0) {
            $trabalho->status = $request->status;

            if($trabalho->save()) {
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'msg' => 'Ocorreu um erro. Atualize a página e tente novamente.']);
            }
        } else {
            return json_encode(['status' => false, 'msg' => 'É necessário primeiro criar o seu perfil de trabalho para depois poder ativá-lo.']);
        }
    }


    public function busca($tipo = null, $palavra_chave = null, $area = null, $tag = null, $offset = null)
    {
        $offset = $offset ? $offset : 0;

        $trabalhos = Trabalho::filtroArea($area)
                            ->filtroTag($tag)
                            ->filtroPalavraChave($palavra_chave)
                            ->filtroTipo($tipo)
                            ->offset($offset)
                            ->limit(2)
                            ->get();

        // Gera a URL
        if(!$palavra_chave && $area) {
            $palavra_chave = 'area';
        }
        $url = '/busca/' . $tipo;
        if($area || $palavra_chave) {
            $url =  $url . '/' . $palavra_chave;
        }
        if($area) {
            $url = $url . '/' . $area . '/' . $tag;
        }

        // Detecta se foi acessado por url ou ajax
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            return view('busca', compact('trabalhos', 'palavra_chave', 'tipo', 'area', 'tag'));
        } else {
            return response()->json([
                'trabalhos' => view('pagination', compact('trabalhos', 'offset'))->render(),
                'url' => $url
            ]);
        }
    }

    public function formBusca(Request $request)
    {
        $palavra_chave = $request->palavra_chave;
        $tipo = $request->tipo;
        $area = $request->area;
        $tag = $request->tag;

        $offset = $request->offset;

        return $this->busca($tipo, $palavra_chave, $area, $tag, $offset);
    }
















    public function teste()
    {
        $areas = Area::select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();

        foreach($areas as $area) {
            echo "<b>AREA - $area->titulo </b><br>";

            $categorias = Categoria::whereHas('area', function($q) use($area) {
                $q->where('slug', $area->slug);
            })->select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();
            foreach($categorias as $categoria) {
                echo "<i>CATEGORIA - $categoria->titulo - $categoria->area_id</i><br>";

                $subcategorias = Subcategoria::whereHas('categoria', function($q) use($categoria) {
                    $q->where('slug', $categoria->slug);
                })->select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();
                foreach($subcategorias as $subcategoria) {
                    echo "SUBCATEGORIA - $subcategoria->titulo<br>";
                }
            }
        }






        /*$row = 1;
        $cat = false;

        if(($handle = fopen(public_path('categorias-estabelecimentos.csv'), "r")) !== FALSE) {
            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;

                for($col = 0; $col < $num; $col++) {
                    if(substr($data[$col], 0, 2) == '**') {
                        $area = new Area;
                        $area->titulo = str_replace('*', '', $data[$col]);
                        $area->slug = str_slug($data[$col], '-');
                        $area->tipo = 2;
                        //$area->save();
                        $area_id = $area->id;

                        echo str_replace('*', '', $data[$col]) . " ------------------------------ area<br />\n";
                    } else if($data[$col] != '' && ($cat || $data[$col] == 'Açougues e frigoríficos')) {
                        $categoria = new Categoria;
                        $categoria->titulo = $data[$col];
                        $categoria->slug = str_slug($data[$col], '-');
                        $categoria->area_id = $area_id;
                        //$categoria->save();
                        $categoria_id = $categoria->id;

                        echo $data[$col] . " ------------------------------ categoria<br />\n";

                        $cat = false;
                    } else {
                        if($data[$col] != '') {
                            $subcategoria = new Subcategoria;
                            $subcategoria->titulo = $data[$col];
                            $subcategoria->slug = str_slug($data[$col], '-');
                            $subcategoria->categoria_id = $categoria_id;
                            //$subcategoria->save();

                            echo $data[$col] . "-------------------------------sub<br />\n";
                        }
                    }

                    if($data[$col] == '') {
                        $cat = true;
                    }
                }
            }

            fclose($handle);
        }*/
    }
}
