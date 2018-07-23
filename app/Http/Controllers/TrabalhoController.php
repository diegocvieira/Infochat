<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trabalho;
use Auth;

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
            $horarios[date("H:i",$tNow)] = date("H:i",$tNow);
            $tNow = strtotime('+30 minutes',$tNow);
        }

        $dias_semana = [
            'Domingo' => 'Domingo',
            'Segunda' => 'Segunda',
            'Terça' => 'Terça',
            'Quarta' => 'Quarta',
            'Quinta' => 'Quinta',
            'Sexta' => 'Sexta',
            'Sábado' => 'Sábado'
        ];

        return response()->json([
            'body' => view('admin.trabalho-config', compact('trabalho', 'tipos', 'horarios', 'dias_semana'))->render()
        ]);
    }

    public function setConfig(Request $request)
    {
        $t = Trabalho::where('user_id', Auth::guard('web')->user()->id)->first();

        $trabalho = isset($t) ? $t->id : new Trabalho;

        $trabalho->nome = $request->nome;
        $trabalho->tipo = $request->tipo;
        $trabalho->area_id = $request->area_id;
        $trabalho->cep = $request->cep;
        $trabalho->bairro = $request->bairro;
        $trabalho->logradouro = $request->logradouro;
        $trabalho->numero = $request->numero;
        $trabalho->complemento = $request->complemento;
        $trabalho->descricao = $request->descricao;
        $trabalho->slug = str_slug($request->nome, '-');

        return $trabalho;
    }

    public function setStatus(Request $request)
    {
        return json_encode(['status' => false, 'status_value' => $request->status]);
    }
}
