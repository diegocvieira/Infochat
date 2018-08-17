<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trabalho;
use Auth;
use App\Area;
use App\Categoria;
use App\Subcategoria;
use App\Cidade;
use App\AvaliarAtendimento;
use App\Message;
use DB;
use App\Avaliar;
use App\Favoritar;
use Cookie;
use App\Chat;

class TrabalhoController extends Controller
{
    public function getConfig()
    {
        $trabalho = Trabalho::where('user_id', Auth::guard('web')->user()->id)->first();

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
        $validator = \Validator::make($request->all(), $this->trabalhoRules(), $this->customMessages());

         if($validator->fails()) {
             $return['msg'] = $validator->errors()->first();
         } else {
             $user_id = Auth::guard('web')->user()->id;

             // Verificar se o trabalho ja existe
             $trabalho = Trabalho::firstOrNew(['user_id' => $user_id]);

             // Buscar a cidade no banco
             $cidade = Cidade::whereHas('estado', function($q) use($request) {
                 $q->where('letter', $request->estado);
             })->where('title', 'LIKE', '%' . $request->cidade . '%')->select('id')->first();
         	$cidade_id = $cidade ? $cidade->id : null;

             $trabalho->cidade_id = $cidade_id;
             $trabalho->user_id = $user_id;
             $trabalho->slug = str_slug($request->slug, '-');
             $trabalho->tipo = $request->tipo;
             $trabalho->nome = $request->nome;
             $trabalho->descricao = $request->descricao;
             $trabalho->logradouro = $request->logradouro;
             $trabalho->numero = $request->numero;
             $trabalho->bairro = $request->bairro;
             $trabalho->complemento = $request->complemento;
             $trabalho->area_id = $request->area_id;
             $trabalho->cep = $request->cep;
             $trabalho->email = $request->email;
             $trabalho->status = isset($request->status) ? 1 : 0;

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

             if($trabalho->save()) {
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
                 if(isset($request->tag) && count($request->tag) <= 10) {
                     foreach($request->tag as $tag) {
                         $trabalho->tags()->create(['tag' => $tag]);
                     }
                 }

                 // Horarios de atendimento
                 $trabalho->horarios()->delete();
                 $horarios = array_map(function($d, $dm, $at, $dt, $an) {
                     return array('dia' => $d, 'de_manha' => $dm, 'ate_tarde' => $at, 'de_tarde' => $dt, 'ate_noite' => $an);
                 }, $request->dia, $request->de_manha, $request->ate_tarde, $request->de_tarde, $request->ate_noite);
                 $count = 0;
                 foreach($horarios as $horario) {
                    if($horario['de_manha']) {
                        $count++;
                    }

                    if($horario['ate_tarde']) {
                        $count++;
                    }

                    if($horario['de_tarde']) {
                        $count++;
                    }

                    if($horario['ate_noite']) {
                        $count++;
                    }

                     if($horario['dia'] && $count >= 2) {
                         $trabalho->horarios()->create($horario);
                     }
                 }

                 $return['msg'] = 'Informações salvas com sucesso!';
             } else {
                 $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
             }
         }

        return json_encode($return);
    }

    public function setStatus(Request $request)
    {
        $trabalho = Trabalho::where('user_id', Auth::guard('web')->user()->id)->first();

        if(count($trabalho) > 0) {
            $trabalho->status = $request->status;

            $trabalho->save();
        }

        return json_encode(true);
    }

    public function formBusca(Request $request)
    {
        $palavra_chave = urlencode($request->palavra_chave);

        return $this->busca($request->tipo, $palavra_chave, $request->area, $request->tag, $request->ordem, $request->offset);
    }

    public function busca($tipo = null, $palavra_chave = null, $area = null, $tag = null, $ordem = null, $offset = null)
    {
        $palavra_chave = urldecode($palavra_chave);
        $tag = urldecode($tag);

        $offset = $offset ? $offset : 0;

        $trabalhos = Trabalho::filtroStatus()
                            ->filtroCidade()
                            ->filtroArea($area)
                            ->filtroTag($tag)
                            ->filtroTipo($tipo)
                            ->filtroOrdem($ordem);

        if($palavra_chave && $palavra_chave != 'area') {
            $header_title = $palavra_chave .' em ' . Cookie::get('sessao_cidade_title') . ' - ' . Cookie::get('sessao_estado_letter') . ' | Infochat';
            $header_desc = 'Clique para ver ' . $palavra_chave . ' em ' . Cookie::get('sessao_cidade_title') . ' - ' . Cookie::get('sessao_estado_letter') . ' no site infochat.com.br';

            $palavra_chave = str_replace('-', ' ', $palavra_chave);

            // separa cada palavra
            $palavra_chave_array = explode(' ', $palavra_chave);

            // se houver mais de 2 palavras e a palavra tiver menos de 4 letras ignora na busca
            foreach($palavra_chave_array as $palavra_cada) {
                if(count($palavra_chave_array) > 2 && strlen($palavra_cada) < 4) {
                    continue;
                }

                $trabalhos = $trabalhos->where('nome', 'LIKE', '%' . $palavra_cada . '%')->orWhereHas('tags', function($q) use($palavra_cada) {
                    $q->where('tag', 'LIKE', '%' . $palavra_cada . '%');
                });
            }
        }

        $trabalhos = $trabalhos->offset($offset)
            ->limit(4)
            ->get();

        // Gera a URL
        if(!$palavra_chave && $area) {
            $palavra_chave = 'area';
        }
        $url = '/busca/' . $tipo;
        if($area || $palavra_chave) {
            $url =  $url . '/' . urlencode($palavra_chave);
        }
        if($area) {
            $url = $url . '/' . $area . '/' . urlencode($tag);
        }

        if(count($trabalhos) > 0) {
            $filtro_ordem = [
                'populares' => 'populares',
                'avaliados' => 'mais bem avaliados',
                'a_z' => 'a - z'
            ];
        }

        // Detecta se foi acessado por url ou ajax
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            return view('pagina-inicial', compact('trabalhos', 'palavra_chave', 'tipo', 'area', 'tag', 'filtro_ordem', 'header_title', 'header_desc'));
        } else {
            return response()->json([
                'trabalhos' => view('inc.list-resultados', compact('trabalhos', 'offset'))->render(),
                'url' => $url
            ]);
        }
    }

    public function show($id)
    {
        $trabalho = Trabalho::find($id);

        pageview($trabalho->id);

        if(Auth::guard('web')->check()) {
            $avaliacao_usuario = Avaliar::where('trabalho_id', $id)
                ->where('user_id', Auth::guard('web')->user()->id)
                ->select('nota', 'descricao')
                ->first();
        }

        return response()->json([
            'trabalho' => view('show-trabalho', compact('trabalho', 'avaliacao_usuario'))->render()
        ]);
    }

    public function favoritar($id)
    {
        $user_id = Auth::guard('web')->user()->id;

        $favoritar = Favoritar::where('trabalho_id', $id)
            ->where('user_id', $user_id)
            ->first();

        if($favoritar) {
            $favoritar->delete();
        } else {
            $f = new Favoritar;

            $f->trabalho_id = $id;
            $f->user_id = $user_id;

            $f->save();
        }

        return json_encode(true);
    }

    private function trabalhoRules()
    {
        $trabalho = Auth::guard('web')->user()->trabalho ? Auth::guard('web')->user()->trabalho->id : '';

        return [
            'slug' => 'required|max:100|unique:trabalhos,slug,' . $trabalho,
            'nome' => 'required|max:100',
            'area_id' => 'required',
            'tipo' => 'required',
            'cep' => 'required|max:10',
            'logradouro' => 'required|max:100',
            'bairro' => 'required|max:50',
            'numero' => 'required|max:10',
            'cidade' => 'required',
            'estado' => 'required',
            'img' => 'image|max:5000'
        ];
    }

    private function customMessages()
    {
        return [
            'nome.required' => 'Informe o nome.',
            'nome.max' => 'O nome deve ter menos de 100 caracteres.',
            'slug.max' => 'A url deve ter menos de 65 caracteres.',
            'slug.required' => 'Informe uma url.',
            'slug.unique' => 'Esta url já está sendo utilizada por outro usuário',
            'img.image' => 'Imagem inválida',
            'img.max' => 'A imagem tem que ter no máximo 5mb.',
            'area_id.required' => 'Selecione uma área.',
            'tipo.required' => 'Selecione um tipo.',
            'cep.required' => 'Informe o CEP.',
            'cep.max' => 'O CEP deve ter menos de 10 caracteres.',
            'logradouro.required' => 'Informe o logradouro.',
            'logradouro.max' => 'O logradouro deve ter menos de 100 caracteres.',
            'bairro.required' => 'Informe o bairro.',
            'bairro.max' => 'O bairro deve ter menos de 50 caracteres.',
            'numero.required' => 'Informe o número.',
            'numero.max' => 'O número deve ter menos de 10 caracteres.',
            'cidade.required' => 'Informe a cidade.',
            'estado.required' => 'Informe o estado.'
        ];
    }









    public function teste()
    {
        /*$mensagem = Mensagem::selectRaw("CONCAT(FLOOR(sum(diferenca)/60),'h',MOD(sum(diferenca),60),'m') as tempo")
    ->whereIn('id', function($query) {
     $query->selectRaw('TIMESTAMPDIFF(MINUTE, m1.created_at, min(m2.created_at)) as diferenca')
        ->from('mensagens as m1')
        ->join('mensagens as m2', 'm1.remetente_id', '=', 'm2.destinatario_id')
        ->where('m2.created_at', '>', 'm1.created_at')
        ->groupBy('m1.id');
     })
     ->where(function($query) {
           $query->selectRaw('min(id)')
                ->from('mensagens')
                ->where('destinatario_id', 2);
     })->toSql();
      //->get();
*/
      /*$mensagem = DB::table(DB::raw('(
          SELECT
    	TIMESTAMPDIFF(MINUTE, m1.created_at, min(m2.created_at)) as diferenca
    FROM
        mensagens m1
    JOIN
        mensagens m2 ON m1.remetente_id = m2.destinatario_id AND m2.remetente_id = m1.destinatario_id AND m2.created_at > m1.created_at
    GROUP BY
        m1.remetente_id,
        m1.destinatario_id,
        m1.created_at,
        m1.id
    ) temp'))
    ->selectRaw("CONCAT(FLOOR(sum(diferenca)/60),'h',MOD(sum(diferenca),60),'m') as tempo")
    /*->where(function($query) {
          $query->selectRaw('min(id)')
               ->from('mensagens')
               ->where('destinatario_id', 2);
    })->toSql();

      return $mensagem;*/
















            /*SELECT
	CONCAT(FLOOR(sum(diferenca)/60),'h',MOD(sum(diferenca),60),'m') as tempo
FROM
(SELECT
	TIMESTAMPDIFF(MINUTE, m1.created_at, min(m2.created_at)) as diferenca
FROM
    mensagens m1
JOIN
    mensagens m2 ON m1.remetente_id = m2.destinatario_id AND m2.remetente_id = m1.destinatario_id AND m2.created_at > m1.created_at
GROUP BY
    m1.remetente_id,
    m1.destinatario_id,
    m1.created_at,
    m1.id) AS table1
WHERE
	(SELECT
		MIN(id)
	FROM
		mensagens
	WHERE
		destinatario_id = 2)*/

        /*$mensagem = Mensagem::
            whereIn('id', function($query) {
                $query->selectRaw('TIMESTAMPDIFF(MINUTE, m1.created_at, min(m2.created_at)) as diferenca')
                    ->from('mensagens as m1')
                    ->join('mensagens as m2', 'm1.remetente_id', '=', 'm2.destinatario_id')
                    ->where('m2.created_at', '>', 'm1.created_at')
                    ->groupBy('m1.remetente_id', 'm1.destinatario_id', 'm1.created_at', 'm1.id');
                })
            ->where(function($query) {
                $query->selectRaw('min(id)')
                    ->from('mensagens')
                    ->where('destinatario_id', 2);
                })
            ->select("CONCAT(FLOOR(sum(diferenca)/60),'h',MOD(sum(diferenca),60),'m') as tempo")
            ->get();

        return $mensagem;*/


        //dd(User::find(1)->trabalho()->toSql());

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
