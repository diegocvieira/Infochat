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
use Auth;
use App\Chat;
use Agent;

class GlobalController extends Controller
{
    public function inicial()
    {
        // Setar poa como cidade default
        if(!Cookie::get('sessao_cidade_slug')) {
            $this->setCidade(4927);

            return redirect()->route('inicial');
        }

        $filtro_ordem = [
            'populares' => 'populares',
            'avaliados' => 'mais bem avaliados',
            'a_z' => 'a - z'
        ];

        $trabalhos = Trabalho::filtroStatus()->filtroCidade()->filtroOrdem('random')->paginate(20);

        if(Agent::isMobile()) {
            return view('mobile.pagina-inicial', compact('filtro_ordem', 'trabalhos'));
        } else {
            return view('pagina-inicial', compact('filtro_ordem', 'trabalhos'));
        }
    }

    public function getCidade(Request $request)
    {
        $cidades = Cidade::with('estado')->where('title', 'like', '%' . $request->nome_cidade . '%')->orderBy('title', 'asc')->get();

        return json_encode(['cidades' => $cidades]);
    }

    public function setCidade($id)
    {
        $ids = [4927,4913,17,67,112,139,170,264,303,315,339,443,635,881,886,930,948,1161,1162,1175,1215,1267,1271,1292,1336,1414,1417,1496,1508,1551,1574,1589,1595,1656,1664,1695,1753,1956,2161,2238,2308,2314,2447,2557,2662,2742,3065,3066,3112,3165,3172,3173,3182,3190,3198,3221,3223,3228,3241,3246,3248,3333,3374,3387,3418,3451,3477,3478,3530,3559,3569,3596,3609,3653,3699,3725,3753,3800,3808,3810,3822,3823,3828,3835,3847,3854,3870,3979,4004,4101,4119,4157,4185,4260,4336,4346,4357,4361,4376,4384,4397,4435,4443,4446,4450,4490,4515,4546,4557,4585,4614,4632,4645,4664,4682,4698,4750,4788,4790,4889,4907,4945,4965,4966,5000,5021,5075,5086,5118,5214,5282,5311,5333,5336,5412,5564];

        if(in_array($id, $ids)) {
            $cidade = Cidade::find($id);

            _setCidade($cidade, $force = true);

            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        //if($cidade->id != 4927) {
            //session()->flash('session_flash_cidade_fechada', 'Cidade fechada');
        //}

        //return redirect()->route('inicial');

        return json_encode($return);
    }

    public function buscaCategorias(Request $request)
    {
        $subcategorias = Subcategoria::where('titulo', 'LIKE', '%' . $request->nome_categoria . '%')->select('titulo', 'slug', DB::raw("'subcategoria' as type"));

        $categorias = Categoria::where('titulo', 'LIKE', '%' . $request->nome_categoria . '%')
            ->select('titulo', 'slug', DB::raw("'categoria' as type"))
            ->union($subcategorias)
            ->get();

        return json_encode(['categorias' => $categorias]);
    }

    public function searchResult($type, $title)
    {
        if($type == 'categoria') {
            $result = Categoria::with('area')->where('titulo', $title)->first();
        } else {
            $result = Subcategoria::with('categoria.area')->where('titulo', $title)->first();
        }

        return json_encode(['result' => $result, 'type' => $type]);
    }

    // Usado no modal trabalho config
    public function getAreas($tipo)
    {
        $areas = Area::where('tipo', $tipo)->get();

        return json_encode(['areas' => $areas]);
    }

    // Usado no modal trabalho config
    public function getCategorias($area)
    {
        $categorias = Categoria::where('area_id', $area)->get();

        return json_encode(['categorias' => $categorias]);
    }

    // Usado no modal trabalho config
    public function getSubcategorias($categoria)
    {
        $subcategorias = Subcategoria::where('categoria_id', $categoria)->get();

        return json_encode(['subcategorias' => $subcategorias]);
    }

    public function asideCategorias($slug, $type)
    {
        $categorias = Categoria::whereHas('area', function($q) use($slug, $type) {
                $q->where('slug', $slug)
                    ->typeFilter($type);
            })
            ->where(DB::raw("(SELECT COUNT(*) FROM tags JOIN trabalhos WHERE trabalhos.id = tags.trabalho_id AND trabalhos.cidade_id = " . Cookie::get('sessao_cidade_id') . " AND tags.tag LIKE CONCAT('%', categorias.titulo, '%'))"), '>=', 1)
            ->orderBy('titulo', 'asc')
            ->select('titulo', 'slug')
            ->distinct()
            ->get();

        return json_encode(['categorias' => $categorias]);
    }

    public function asideSubcategorias($slug)
    {
        $subcategorias = Subcategoria::whereHas('categoria', function($q) use($slug) {
                $q->where('slug', $slug);
            })
            ->where(DB::raw("(SELECT COUNT(*) FROM tags JOIN trabalhos WHERE trabalhos.id = tags.trabalho_id AND trabalhos.cidade_id = " . Cookie::get('sessao_cidade_id') . " AND tags.tag LIKE CONCAT('%', subcategorias.titulo, '%'))"), '>=', 1)
            ->orderBy('titulo', 'asc')
            ->select('titulo', 'slug')
            ->distinct()
            ->get();

        return json_encode(['subcategorias' => $subcategorias]);
    }

    public function asideAreas($type)
    {
        $areas = Area::typeFilter($type)->ordered()->get();

        return json_encode(['areas' => $areas]);
    }
}
