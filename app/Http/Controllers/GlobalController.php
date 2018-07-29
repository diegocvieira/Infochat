<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Subcategoria;
use App\Cidade;
use App\Area;
use App\Trabalho;
use Cookie;
use DB;
use App\Mensagem;
use Auth;

class GlobalController extends Controller
{
    public function inicial()
    {
        if(!Cookie::get('sessao_cidade_slug')) {
            $cidade = Cidade::find(4913);

            _setCidade($cidade, $force = true);
        }

        $filtro_ordem = [
            'populares' => 'populares',
            'avaliados' => 'mais bem avaliados',
            'a_z' => 'a - z'
        ];

        $trabalhos = Trabalho::limit(20)->get();

        return view('pagina-inicial', compact('filtro_ordem', 'trabalhos'));
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

    public function buscaCategorias(Request $request)
    {
        $subcategorias = Subcategoria::where('titulo', 'LIKE', '%' . $request->nome_categoria . '%')
                                    ->select('titulo', 'slug');
        $categorias = Categoria::where('titulo', 'LIKE', '%' . $request->nome_categoria . '%')
                                ->select('titulo', 'slug')
                                ->union($subcategorias)
                                ->get();

        return json_encode(['categorias' => $categorias]);
    }

    public function getAreas($tipo)
    {
        $areas = Area::where('tipo', $tipo)->get();

        return json_encode(['areas' => $areas]);
    }

    public function getCategorias($area)
    {
        $categorias = Categoria::where('area_id', $area)->get();

        return json_encode(['categorias' => $categorias]);
    }

    public function getSubcategorias($categoria)
    {
        $subcategorias = Subcategoria::where('categoria_id', $categoria)->get();

        return json_encode(['subcategorias' => $subcategorias]);
    }

    public function asideCategorias($slug)
    {
        $categorias = Categoria::whereHas('area', function($q) use($slug) {
            $q->where('slug', $slug);
        })->select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();

        return json_encode(['categorias' => $categorias]);
    }

    public function asideSubcategorias($slug)
    {
        $subcategorias = Subcategoria::whereHas('categoria', function($q) use($slug) {
            $q->where('slug', $slug);
        })->select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();

        return json_encode(['subcategorias' => $subcategorias]);
    }

    public function asideAreas($tipo)
    {
        if($tipo == 'profissionais') {
            $tipo = 1;
        } else if($tipo == 'estabelecimentos') {
            $tipo = 2;
        } else {
            $tipo = '';
        }

        if($tipo) {
            $areas = Area::where('tipo', $tipo)->select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();
        } else {
            $areas = Area::select('titulo', 'slug')->distinct()->orderBy('titulo', 'asc')->get();
        }

        return json_encode(['areas' => $areas]);
    }
}
