<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaliar;
use App\AvaliarAtendimento;
use Auth;
use App\Trabalho;

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

            $avaliar->save();

            session(['atendimento' => true]);
            session(['atendimento_' . $trabalho->id => $request->like]);
        }
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
                $return['nome'] = Auth::guard('web')->user()->nome;
                $return['imagem'] = Auth::guard('web')->user()->imagem;
                $return['nota'] = $request->nota;
                $return['data'] = date('d/m/Y');
                $return['descricao'] = $request->descricao;
            } else {
                $return['status'] = false;
            }

            return json_encode($return);
        }
    }

    public function list($id, $offset)
    {
        $offset = $offset ? $offset : 0;

        $avaliacoes = Avaliar::with('user')
            ->where('trabalho_id', $id)
            ->offset($offset)
            ->limit(20)
            ->whereNotNull('descricao')
            ->orderBy('created_at', 'desc')
            ->get();

        return json_encode(['avaliacoes' => $avaliacoes]);
    }
}
