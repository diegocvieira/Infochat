<?php
function _setCidade($cidade, $force = false)
{
    if($cidade != null){
    	$cidade->load('estado');

        if($force == true or !Cookie::get('sessao_cidade_slug')) {
            Cookie::queue('sessao_cidade_id', $cidade->id, '525600');
            Cookie::queue('sessao_cidade_title', $cidade->title, '525600');
            Cookie::queue('sessao_cidade_slug', $cidade->slug, '525600');

            Cookie::queue('sessao_estado_id', $cidade->estado->id, '525600');
            Cookie::queue('sessao_estado_title', $cidade->estado->title, '525600');
            Cookie::queue('sessao_estado_slug', $cidade->estado->slug, '525600');
            Cookie::queue('sessao_estado_letter', $cidade->estado->letter, '525600');
            Cookie::queue('sessao_estado_letter_lc', $cidade->estado->letter_lc, '525600');
        }

    	return;
    }
}

function format_pageviews($n, $precision = 1)
{
    if($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }

    // Remove zeros adicionais depois do decimal
    if($precision > 0) {
        $n_format = str_replace('.' . str_repeat('0', $precision), '', $n_format);
    }

    return $n_format . $suffix;
}

function format_horario($horario)
{
    return substr($horario, 0, 5);
}

function diaHorario($dia)
{
    switch($dia) {
        case '6':
            $dia = 'Domingo';
            break;
        case '0':
            $dia = 'Segunda';
            break;
        case '1':
            $dia = 'Terça';
            break;
        case '2':
            $dia = 'Quarta';
            break;
        case '3':
            $dia = 'Quinta';
            break;
        case '4':
            $dia = 'Sexta';
            break;
        case '5':
            $dia = 'Sábado';
    }

    return $dia;
}

function diaSemana($data)
{
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

    $data = date('Y-m-d', strtotime($data));

    if($data == date('Y-m-d')) {
        $dia = 'HOJE';
    } else if($data == date('Y-m-d', strtotime('-1 day'))) {
        $dia = 'ONTEM';
    } else {
        if(date('Y-m-d', strtotime('-7 day')) > $data) {
            $dia = date('d/m/Y', strtotime($data));
        } else {
            $dia = strftime('%A', strtotime($data));

            if($dia != 'sábado' && $dia != 'domingo') {
                $dia = $dia . '-feira';
            }
        }
    }

    return $dia;
}

// Contabilizar pageview
function pageview($id)
{
    $trabalho = \App\Trabalho::find($id);

    $trabalho->pageviews = $trabalho->pageviews + 1;

    $trabalho->save();
}

// Pegar apenas o primeiro nome
function firstName($name)
{
    $array = explode(' ', trim($name));

    return $array[0];
}

function _getOriginalImage($image)
{
    return str_replace('thumb', 'original', $image);
}

function _uploadImage($file, $old_file)
{
    $path = public_path() . '/uploads/' . Auth::guard('web')->user()->id;
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
