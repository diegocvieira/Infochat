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

        $areas = Area::where('tipo', $trabalho->tipo)->pluck('titulo', 'id');

        return response()->json([
            'body' => view('admin.trabalho-config', compact('areas', 'trabalho', 'tipos', 'horarios', 'dias_semana'))->render()
        ]);
    }

    public function setConfig(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $slug = str_slug($request->slug, '-');

        // Validar o slug
        $slug_validate = Trabalho::where('slug', $slug)->where('user_id', '!=', $user_id)->count();

        if($slug_validate == 0 && $request->nome && $slug && $request->area_id && $request->tipo) {
            // Verificar se o trabalho ja existe
            $t = Trabalho::where('user_id', $user_id)->first();
            $trabalho = isset($t) ? $t : new Trabalho;

            // Buscar a cidade no banco
            $cidade = Cidade::whereHas('estado', function($q) use($request) {
                $q->where('letter', $request->estado);
            })->where('title', 'LIKE', '%' . $request->cidade . '%')->select('id')->first();
        	$cidade_id = $cidade ? $cidade->id : null;

            $request['cidade_id'] = $cidade_id;
            $request['user_id'] = $user_id;
            $request['slug'] = $slug;

            if(!empty($request->img)) {
                if(isset($t) && $t->imagem) {
                    unlink('uploads/perfil/' . $t->imagem);
                }

                // Salva a imagem e move para a pasta
                $file = $request->img;
                $destinationPath = 'uploads'; // Caminho da pasta
                $fileName = date('YmdHis') . microtime(true) . rand(111111111, 999999999) . '.' . $file->getClientOriginalExtension(); // Renomear
                $file->move($destinationPath, $fileName); // Mover para a pasta

                $request['imagem'] = $fileName;
            }

            // Escolher entre create e update
            if(isset($t)) {
                $trabalho->update($request->all());
                $msg = 'Informações alteradas com sucesso!';
            } else {
                $trabalho->create($request->all());
                $msg = 'Perfil criado com sucesso!';
            }

            // Telefones
            $trabalho->telefones()->delete();
            foreach($request->fone as $fone) {
                if(!empty($fone)) {
                    $trabalho->telefones()->create(['fone' => $fone]);
                }
            }

            // Redes sociais
            $trabalho->redes()->delete();
            foreach($request->social as $social) {
                if(!empty($social)) {
                    $trabalho->redes()->create(['url' => $social]);
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
            $msg = 'Este slug já está em uso. Escolha outro e tente novamente.';
        }

        return json_encode(['msg' => $msg]);
    }

    public function setStatus(Request $request)
    {
        $trabalho = Trabalho::where('user_id', Auth::guard('web')->user()->id)->first();

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























    public function teste()
    {
        $dia = ['Domingo', 'Quarta'];
        $de_manha = ["08:00", "09:00"];
        $ate_tarde = ["13:00", "14:00"];
        $de_tarde = ["13:00", "14:00"];
        $ate_noite = ["19:00", "18:00"];
        $novo = array_map(function($d, $dm, $at, $dt, $an) {
            return array('dia' => $d, 'de_manha' => $dm, 'ate_tarde' => $at, 'de_tarde' => $dt, 'ate_noite' => $an);
        }, $dia, $de_manha, $ate_tarde, $de_tarde, $ate_noite);

        return $novo;

        /*$row = 1;
        $cat = false;

        if(($handle = fopen(public_path('categorias-profissionais.csv'), "r")) !== FALSE) {
            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;

                for($col = 0; $col < $num; $col++) {
                    if(substr($data[$col], 0, 2) == '**') {
                        $area = new Area;
                        $area->titulo = str_replace('*', '', $data[$col]);
                        $area->slug = str_slug($data[$col], '-');
                        $area->tipo = 1;
                        //$area->save();
                        $area_id = $area->id;

                        echo str_replace('*', '', $data[$col]) . " ------------------------------ area<br />\n";
                    } else if($data[$col] != '' && ($cat || $data[$col] == 'Artista')) {
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
