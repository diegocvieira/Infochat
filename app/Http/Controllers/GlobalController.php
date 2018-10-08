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
        if(in_array($id, _openCitys())) {
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

    public function automaticRegister(Request $request)
    {
        $file_name = $request->file->getClientOriginalName();
        $request->file->move(public_path(), $file_name);

        if(($handle = fopen(public_path() . '/' . $file_name, 'r')) !== FALSE) {
            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $user = new \App\User;
                $user->nome = $data[0];
                $user->email = $data[1];
                $user->password = bcrypt(time() . rand(0, 99999));
                $user->claimed = 0;
                $user->save();

                $work = new Trabalho;
                $work->user_id = $user->id;
                $work->tipo = $request->type;
                $work->nome = $data[0];
                $work->cidade_id = 4913;
                $work->status = 1;
                $work->area_id = $request->area;
                $work->slug = str_slug($data[0], '-');
                $work->save();

                $work->tags()->create(['tag' => $request->categorie]);
            }

            fclose($handle);

            unlink(public_path() . '/' . $file_name);

            return redirect('adm/automatic');
        }
    }

    public function automaticEmails(Request $request)
    {
        $tag = $request->categorie;
        $logged_user = Auth::guard('web')->user()->id;

        $works = Trabalho::whereHas('tags', function($q) use($tag) {
                $q->where('tag', $tag);
            })
            ->whereHas('user', function($q) use($tag) {
                $q->where('claimed', 0);
            })
            ->get();

        foreach($works as $work) {
            $chat = new \App\Chat;
            $chat->from_id = $logged_user;
            $chat->to_id = $work->user_id;
            $chat->created_at = date('Y-m-d H:i:s');
            $chat->save();

            $message = new \App\Message;
            $message->chat_id = $chat->id;
            $message->user_id = $logged_user;
            $message->message = $request->message;
            $message->created_at = date('Y-m-d H:i:s');
            $message->save();

            $email = $work->user->email;

            $client['name'] = Auth::guard('web')->user()->nome;
            $client['image'] = Auth::guard('web')->user()->imagem;
            $client['message'] = $request->message;
            $client['id'] = $logged_user;

            $claimed_url = url('/') . '/reivindicar-conta/check/' . app('App\Http\Controllers\ClaimedController')->createToken($email);
            $work_url = route('show-chat', $work->slug);

            \Mail::send('emails.new_message_claimed', ['client' => $client, 'work_url' => $work_url, 'claimed_url' => $claimed_url], function($q) use($email) {
                $q->from('no-reply@infochat.com.br', 'Infochat');
                $q->to($email)->subject('Nova mensagem');
            });
        }

        return redirect('adm/automatic');
    }

    public function automaticImages(Request $request)
    {
        foreach($request->images as $key_image => $image) {
            foreach($request->emails as $key_email => $email) {
                if($key_image == $key_email) {
                    $work = Trabalho::whereHas('user', function($q) use($email) {
                        $q->where('email', $email);
                    })->first();

                    $work->imagem = $this->uploadImage($image, $work->imagem, $work->user->id);
                    $work->save();
                }
            }
        }

        return redirect('adm/automatic');
    }

    public function uploadImage($file, $old_file, $id)
    {
        $path = public_path() . '/uploads/' . $id;
        $microtime = microtime(true);
        $filename_thumb = $microtime . '.thumb.jpg';
        $filename_original = $microtime . '.original.jpg';

        // Remove old images
        if($old_file) {
            $old_image_thumb = $path . '/' . $old_file;
            $old_image_original = $path . '/' . str_replace('thumb', 'original', $old_file);

            if(file_exists($old_image_thumb)) {
                unlink($old_image_thumb);
            }

            if(file_exists($old_image_original)) {
                unlink($old_image_original);
            }
        }

        // Create the folder if not exists
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        for($i = 1; $i <= 2; $i++) {
            $image = new \Imagick($file->path());
            $image->setColorspace(\Imagick::COLORSPACE_SRGB);
            $image->setImageFormat('jpg');
            $image->stripImage();
            $image->setImageCompressionQuality(70);
            $image->setSamplingFactors(array('2x2', '1x1', '1x1'));
            $image->setInterlaceScheme(\Imagick::INTERLACE_JPEG);

            if($i == 1) {
                // THUMB
                $image->cropThumbnailImage(78, 78);
                $image->writeImage($path . '/' . $filename_thumb);
            } else {
                // ORIGINAL
                $image->cropThumbnailImage(250, 250);
                $image->writeImage($path . '/' . $filename_original);
            }

            $image->destroy();
        }

        return $filename_thumb;
    }
}
