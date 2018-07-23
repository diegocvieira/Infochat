<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Subcategoria;
use App\Cidade;
use Cookie;
use DB;

class GlobalController extends Controller
{
    public function inicial()
    {
        if(!Cookie::get('sessao_cidade_slug')) {
            $cidade = Cidade::find(4913);

            _setCidade($cidade, $force = true);
        }

        $categorias = Categoria::get();

        return view('pagina-inicial', compact('categorias'));
    }

    public function getCidade(Request $request)
    {
        $cidades = Cidade::with('estado')->where('title', 'like', '%' . $request->nome_cidade . '%')->orderBy('title', 'asc')->get();

        return json_encode(['cidades' => $cidades]);
    }

    public function setCidade($id)
    {
        $cidade = Cidade::find($id);

        _setCidade($cidade, $force = true);

        return redirect()->route('inicial');
    }

    public function getCategoria(Request $request)
    {
        $callback = function($query) use($request) {
            $query->where('titulo', 'LIKE', '%' . $request->nome_categoria . '%');
        };

        $categorias = Categoria::where('titulo', 'LIKE', '%' . $request->nome_categoria . '%')
        ->orWhereHas('subcategorias', $callback)->with(['subcategorias' => $callback])->get();

        return json_encode(['categorias' => $categorias]);
    }
}
