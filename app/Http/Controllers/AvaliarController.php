<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaliar;
use App\AvaliarAtendimento;
use Auth;
use App\Trabalho;
use Illuminate\Pagination\Paginator;
use Agent;

class AvaliarController extends Controller
{
    public function avaliarAtendimento(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $trabalho = Trabalho::findOrFail($request->trabalho_id);

        if($user_id != $trabalho->user_id) {
            $avaliar = new AvaliarAtendimento;

            $avaliar->trabalho_id = $trabalho->id;
            $avaliar->user_id = $user_id;

            if($request->like) {
                $avaliar->likes = 1;
            } else {
                $avaliar->dislikes = 1;
            }

            if($avaliar->save()) {
                session(['atendimento' => true]);
                session(['atendimento_' . $trabalho->id => $request->like]);

                $return['status'] = true;
            } else {
                $return['status'] = false;
                $return['msg'] = 'Ocorreu um erro. Atualize a página e tente novamente.';
            }
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não é possível avaliar você mesmo.';
        }

        return json_encode($return);
    }

    public function avaliar(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $trabalho = Trabalho::findOrFail($request->trabalho_id);

        if($user_id != $trabalho->user_id) {
            $avaliar = Avaliar::firstOrNew(['trabalho_id' => $request->trabalho_id, 'user_id' => $user_id]);

            $avaliar->trabalho_id = $trabalho->id;
            $avaliar->user_id = $user_id;
            $avaliar->nota = $request->nota;
            $avaliar->descricao = $request->descricao;

            if($avaliar->save()) {
                $return['status'] = true;
                $return['msg'] = 'Avaliação realizada com sucesso!';
                $return['nome'] = Auth::guard('web')->user()->nome;
                $return['imagem'] = Auth::guard('web')->user()->imagem;
                $return['nota'] = $request->nota;
                $return['data'] = date('d/m/Y');
                $return['descricao'] = $request->descricao;
            } else {
                $return['status'] = false;
                $return['msg'] = 'Ocorreu um erro. Atualize a página e tente novamente.';
            }
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não é possível avaliar você mesmo.';
        }

        return json_encode($return);
    }

    public function list($id, $page)
    {
        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        $avaliacoes = Avaliar::where('trabalho_id', $id)
            ->whereNotNull('descricao')
            ->orderBy('id', 'desc')
            ->paginate(20);

        if($page == 1) {
            return $avaliacoes;
        } else {
            if(Agent::isMobile()) {
                return response()->json([
                    'avaliacoes' => view('mobile.list-avaliacoes', compact('avaliacoes'))->render()
                ]);
            } else {
                return response()->json([
                    'avaliacoes' => view('list-avaliacoes', compact('avaliacoes'))->render()
                ]);
            }
        }
    }
}
