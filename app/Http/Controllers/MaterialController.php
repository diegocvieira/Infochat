<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZIPARCHIVE;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Auth;
use Agent;

class MaterialController extends Controller
{
    public function preview()
    {
        $sizes = [
            'a5' => 'Tamanho A5 = 14.8 x 21 cm',
            'a4' => 'Tamanho A4 = 21.0 x 29.7 cm',
            'a3' => 'Tamanho A3 = 29.7 x 42 cm',
            'all' => 'Baixar tudo'
        ];

        return response()->json([
            'material' => view('admin.material', compact('sizes'))->render()
        ]);
    }

    public function create($folder = 'a4')
    {
        $trabalho_slug = Auth::guard('web')->user()->trabalho->slug;
        $path = public_path() . '/material-divulgacao/' . Auth::guard('web')->user()->id;
        $zipFile = $path . '/' . $trabalho_slug . '_' . $folder . '.zip';

        if(!file_exists($zipFile)) {
            for($i = 1; $i <= 2; $i++) {
                $image = new \Imagick(resource_path('assets/img/material-divulgacao/' . $folder . '/' . $i . '.jpg'));
                $draw = new \ImagickDraw();

                // Font properties
                $font_color = $i == 1 ? '#fff' : '#23418c';

                $draw->setFillColor($font_color);
                $draw->setFont(resource_path('assets/fonts/AgencyFB-Bold.ttf'));
                $draw->setFontSize(150);

                // Create text
                $image->annotateImage($draw, 1310, 4195, 0, $trabalho_slug);

                if(!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $image->writeImage($path . '/' . $i . '.jpg');
            }

            $filesToDelete = array();

            $zip = new ZipArchive();

            $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY);

            foreach($files as $file) {
                // Skip directories (they would be added automatically)
                if(!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = explode($path . '/', $filePath);
                    $relativePath = $relativePath[1];

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);

                    $filesToDelete[] = $filePath;
                }
            }

            $zip->close();

            foreach($filesToDelete as $file) {
                unlink($file);
            }
        }
    }
}
