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
use App\Estado;
use Agent;

class TrabalhoController extends Controller
{
    public function getConfig()
    {
        $trabalho = Trabalho::where('user_id', Auth::guard('web')->user()->id)->first();

        $states = Estado::pluck('title', 'id');

        if(isset($trabalho)) {
            $cities = Cidade::where('estado_id', $trabalho->cidade->estado->id)->pluck('title', 'id');
        } else {
            $cities = [];
        }

        /*$tipos = [
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
        ];*/

        /*if(isset($trabalho)) {
            $areas = Area::where('tipo', $trabalho->tipo)->pluck('titulo', 'id');

            $categorias = Categoria::where('area_id', $trabalho->area_id)->get();
        }*/

        if(Agent::isDesktop()) {
            return response()->json([
                'body' => view('admin.trabalho-config', compact('trabalho', 'states', 'cities'))->render()
            ]);
        } else {
            return view('mobile.admin.work-config', compact('trabalho', 'states', 'cities'));
        }
    }

    public function setConfig(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->trabalhoRules(), $this->customMessages());

        if($validator->fails()) {
            $return['msg'] = $validator->errors()->first();
        } else if(!isset($request->tag)) {
            $return['msg'] = 'Informe pelo menos uma palavra-chave.';
        } else {
            $user_id = Auth::guard('web')->user()->id;

            $trabalho = Trabalho::firstOrNew(['user_id' => $user_id]);

            // Buscar a cidade no banco
            //$cidade = Cidade::whereHas('estado', function($q) use($request) {
                //$q->where('letter', $request->estado);
            //})->where('title', 'LIKE', '%' . $request->cidade . '%')->select('id')->first();

            //if(!$cidade) {
                //$return['msg'] = 'Não identificamos a sua cidade, confira o nome e tente novamente. Se o problema persistir, entre em contato conosco.';
            //} else if($cidade && !in_array($cidade->id, _openCitys())) {
                //$return['msg'] = 'Ainda não estamos operando nesta cidade.' . "<br>" . 'Volte outro dia, estamos trabalhando para levar o infochat para o mundo todo.';
            //} else {
                $trabalho->cidade_id = $request->cidade;
                $trabalho->user_id = $user_id;
                $trabalho->slug = str_slug($request->slug, '-');
                //$trabalho->tipo = $request->tipo;
                $trabalho->nome = $request->nome;
                $trabalho->descricao = $request->descricao;
                //$trabalho->logradouro = $request->logradouro;
                //$trabalho->numero = $request->numero;
                //$trabalho->bairro = $request->bairro;
                //$trabalho->complemento = $request->complemento;
                //$trabalho->area_id = $request->area_id;
                //$trabalho->cep = $request->cep;
                //$trabalho->email = $request->email;
                $trabalho->status = isset($request->status) ? 1 : 0;

                if(!empty($request->img)) {
                    $trabalho->imagem = _uploadImage($request->img, $trabalho->imagem);
                }

                if($trabalho->save()) {
                    // Telefones
                    /*$trabalho->telefones()->delete();
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
                    }*/

                    // Tags
                    $trabalho->tags()->delete();
                    if(isset($request->tag) && count($request->tag) <= 10) {
                        foreach($request->tag as $tag) {
                            $trabalho->tags()->create(['tag' => $tag]);
                        }
                    }

                    // Horarios de atendimento
                    /*$trabalho->horarios()->delete();
                    $horarios = array_map(function($d, $dm, $at, $dt, $an) {
                        return array('dia' => $d, 'de_manha' => $dm, 'ate_tarde' => $at, 'de_tarde' => $dt, 'ate_noite' => $an);
                    }, $request->dia, $request->de_manha, $request->ate_tarde, $request->de_tarde, $request->ate_noite);
                    foreach($horarios as $horario) {
                        if(is_numeric($horario['dia']) && ($horario['de_manha'] && $horario['ate_tarde'] || $horario['de_tarde'] && $horario['ate_noite'])) {
                            $trabalho->horarios()->create($horario);
                        }
                    }*/

                    $return['msg'] = 'Informações salvas com sucesso!';
                } else {
                    $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
                }
            //}
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
        $city = Cookie::get('sessao_cidade_slug');
        $state = Cookie::get('sessao_estado_letter_lc');

        return $this->busca($city, $state, $palavra_chave);
    }

    public function busca($city_slug, $state_letter_lc, $palavra_chave = null)
    {
        // Verifica e seta a requisicao se for uma cidade diferente
        if($city_slug != Cookie::get('sessao_cidade_slug') || $state_letter_lc != Cookie::get('sessao_estado_letter_lc')) {
            $city = Cidade::where('slug', $city_slug)
                ->whereHas('estado', function($q) use($state_letter_lc) {
                    $q->where('letter_lc', $state_letter_lc);
                })->first();

            if(count($city) > 0) {
                _setCidade($city, $force = true);

                return redirect(action('TrabalhoController@busca', [$city_slug, $state_letter_lc, $palavra_chave]));
            } else {
                return view('errors.404');
            }
        }

        // Desformatar para pesquisar
        $palavra_chave = urldecode($palavra_chave);

        $trabalhos = Trabalho::filtroStatus()
            ->filtroCidade();

        if($palavra_chave) {
            // SEO
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

                $trabalhos = $trabalhos->where(function($q) use($palavra_cada) {
                    $q->where('nome', 'LIKE', '%' . $palavra_cada . '%')->orWhereHas('tags', function($q) use($palavra_cada) {
                            $q->where('tag', 'LIKE', '%' . $palavra_cada . '%');
                        });
                });
            }
        }

        $trabalhos = $trabalhos->paginate(10);

        // Gera a URL
        //$url = '/busca/' . $city_slug . '/' . $state_letter_lc;
        //if($palavra_chave) {
            //$url =  $url . '/' . urlencode($palavra_chave);
        //}

        $url = $trabalhos->currentPage() == 1 ? '/busca/' . $city_slug . '/' . $state_letter_lc . '/' . urlencode($palavra_chave): $trabalhos->url($trabalhos->currentPage());

        /*if(count($trabalhos) > 0) {
            $filtro_ordem = [
                'populares' => 'populares',
                'avaliados' => 'mais bem avaliados',
                'a_z' => 'a - z'
            ];
        }*/

        // Detecta se foi acessado por url ou ajax
        if(!\Request::ajax()) {
            if(Agent::isMobile()) {
                return view('mobile.pagina-inicial', compact('trabalhos', 'palavra_chave', 'header_title', 'header_desc'));
            } else {
                return view('pagina-inicial', compact('trabalhos', 'palavra_chave', 'header_title', 'header_desc'));
            }
        } else {
            if(Agent::isMobile()) {
                return response()->json([
                    'trabalhos' => view('mobile.inc.list-resultados', compact('trabalhos'))->render(),
                    'url' => $url,
                    'header_title' => $header_title
                ]);
            } else {
                return response()->json([
                    'trabalhos' => view('inc.list-resultados', compact('trabalhos'))->render(),
                    'url' => $url,
                    'header_title' => $header_title
                ]);
            }
        }
    }

    public function show($slug)
    {
        $trabalhos = Trabalho::filtroStatus()
            ->where('slug', $slug)
            ->withCount(['avaliacoes as nota_avaliacao' => function($query) {
                $query->select(DB::raw('ROUND((SUM(nota) / COUNT(id)), 1)'));
            }])
            ->withCount(['notas_atendimento as nota_atendimento' => function($query) {
                $query->select(DB::raw('CEILING((SUM(likes) * 100) / (SUM(likes) + SUM(dislikes)))'));
            }])
            ->paginate(1);

        if(count($trabalhos) > 0) {
            // SEO
            $header_title = $trabalhos->first()->nome . ' - ' . Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') . ' | Infochat';
            $header_desc = 'Clique para ver o perfil de ' . $trabalhos->first()->nome . ' em ' . Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') . ' no site infochat.com.br';

            pageview($trabalhos->first()->id);

            if(Cookie::get('sessao_cidade_id') != $trabalhos->first()->cidade_id || Cookie::get('sessao_estado_letter_lc') != $trabalhos->first()->cidade->estado->letter_lc) {
                _setCidade($trabalhos->first()->cidade, $force = true);

                return redirect(action('TrabalhoController@show', $slug));
            }

            if(Agent::isDesktop()) {
                $destinatario = $trabalhos->first();
                $palavra_chave = $trabalhos->first()->nome;
                $tipo = 'trabalho';
                $destinatario_id = $trabalhos->first()->user_id;

                return view('pagina-inicial', compact('palavra_chave', 'trabalhos', 'messages', 'tipo', 'destinatario', 'destinatario_id', 'header_desc', 'header_title'));
            } else {
                //$avaliacoes = app('App\Http\Controllers\AvaliarController')->list($trabalho->id, 1);

                $work = $trabalhos->first();

                if(Auth::guard('web')->check()) {
                    $avaliacao_usuario = Avaliar::where('trabalho_id', $work->id)
                        ->where('user_id', Auth::guard('web')->user()->id)
                        ->select('nota', 'descricao')
                        ->first();
                }

                return view('mobile.show-work', compact('work', 'avaliacao_usuario', 'header_desc', 'header_title'));
            }
        } else {
            return view('errors.404');
        }
    }

    public function showDesktop($slug)
    {
        $work = Trabalho::filtroStatus()
            ->where('slug', $slug)
            ->withCount(['avaliacoes as nota_avaliacao' => function($query) {
                $query->select(DB::raw('ROUND((SUM(nota) / COUNT(id)), 1)'));
            }])
            ->withCount(['notas_atendimento as nota_atendimento' => function($query) {
                $query->select(DB::raw('CEILING((SUM(likes) * 100) / (SUM(likes) + SUM(dislikes)))'));
            }])
            ->first();

        if($work) {
            pageview($work->id);

            if(Auth::guard('web')->check()) {
                $avaliacao_usuario = Avaliar::where('trabalho_id', $work->id)
                    ->where('user_id', Auth::guard('web')->user()->id)
                    ->select('nota', 'descricao')
                    ->first();
            }

            return response()->json([
                'work' => view('show-trabalho', compact('work', 'avaliacao_usuario'))->render()
            ]);
        } else {
            return view('errors.404');
        }
    }

    /*public function favoritar($id)
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
    }*/

    private function trabalhoRules()
    {
        $trabalho = Auth::guard('web')->user()->trabalho ? Auth::guard('web')->user()->trabalho->id : '';

        return [
            'slug' => 'required|max:100|unique:trabalhos,slug,' . $trabalho,
            'nome' => 'required|max:100',
            //'area_id' => 'required',
            //'tipo' => 'required',
            //'cep' => 'max:10',
            //'logradouro' => 'max:100',
            //'bairro' => 'max:50',
            //'numero' => 'max:10',
            'cidade' => 'required',
            //'estado' => 'required',
            'img' => 'image|max:5000',
            'descricao' => 'max:300'
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
            //'area_id.required' => 'Selecione uma área.',
            //'tipo.required' => 'Selecione um tipo.',
            //'cep.required' => 'Informe o CEP.',
            //'cep.max' => 'O CEP deve ter menos de 10 caracteres.',
            //'logradouro.required' => 'Informe o logradouro.',
            //'logradouro.max' => 'O logradouro deve ter menos de 100 caracteres.',
            //'bairro.required' => 'Informe o bairro.',
            //'bairro.max' => 'O bairro deve ter menos de 50 caracteres.',
            //'numero.required' => 'Informe o número.',
            //'numero.max' => 'O número deve ter menos de 10 caracteres.',
            'cidade.required' => 'Informe a cidade.',
            //'estado.required' => 'Informe o estado.'
            'descricao.max' => 'A descrição deve ter menos de 300 caracteres.'
        ];
    }
}
