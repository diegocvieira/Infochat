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

function _openCitys()
{
    $ids = [4927,4913,17,67,112,139,170,264,303,315,339,443,635,881,886,930,948,1161,1162,1175,1215,1267,1271,1292,1336,1414,1417,1496,1508,1551,1574,1589,1595,1656,1664,1695,1753,1956,2161,2238,2308,2314,2447,2557,2662,2742,3065,3066,3112,3165,3172,3173,3182,3190,3198,3221,3223,3228,3241,3246,3248,3333,3374,3387,3418,3451,3477,3478,3530,3559,3569,3596,3609,3653,3699,3725,3753,3800,3808,3810,3822,3823,3828,3835,3847,3854,3870,3979,4004,4101,4119,4157,4185,4260,4336,4346,4357,4361,4376,4384,4397,4435,4443,4446,4450,4490,4515,4546,4557,4585,4614,4632,4645,4664,4682,4698,4750,4788,4790,4889,4907,4945,4965,4966,5000,5021,5075,5086,5118,5214,5282,5311,5333,5336,5412,5564];

    return $ids;
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

// Check temporary account
function _temporaryAccount()
{
    if(strpos(Auth::guard('web')->user()->email, '@unlogged') !== false) {
        return true;
    } else {
        return false;
    }
}
