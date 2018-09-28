<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZIPARCHIVE;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Auth;

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

    public function create($size)
    {
        if($size == 'a5') {
            $folders = [
                'a5' => [
                    'x' => 650,
                    'y' => 2110,
                    'font-size' => 100
                ]
            ];
        } else if($size == 'a4') {
            $folders = [
                'a4' => [
                    'x' => 925,
                    'y' => 2980,
                    'font-size' => 140
                ]
            ];
        } else if($size == 'a3') {
            $folders = [
                'a3' => [
                    'x' => 1320,
                    'y' => 4220,
                    'font-size' => 200
                ]
            ];
        } else {
            $folders = [
                'a5' => [
                    'x' => 650,
                    'y' => 2110,
                    'font-size' => 100
                ],
                'a4' => [
                    'x' => 925,
                    'y' => 2980,
                    'font-size' => 140
                ],
                'a3' => [
                    'x' => 1320,
                    'y' => 4220,
                    'font-size' => 200
                ]
            ];
        }

        $trabalho_slug = Auth::guard('web')->user()->trabalho->slug;
        $user_id = Auth::guard('web')->user()->id;
        $path = public_path() . '/material-divulgacao/' . $user_id . '/' . $size;
        $zip_file = public_path() . '/material-divulgacao/' . $user_id . '/' . $trabalho_slug . '_' . $size . '.zip';

        if(!file_exists($zip_file)) {
            foreach($folders as $folder => $folder_params) {
                $path_imagick = $size == 'all' ? $path . '/' . $folder : $path;

                for($i = 1; $i <= 2; $i++) {
                    $image = new \Imagick(resource_path('assets/img/material-divulgacao/' . $folder . '/' . $i . '.jpg'));
                    $draw = new \ImagickDraw();

                    // Font properties
                    $font_color = $i == 1 ? '#fff' : '#23418c';

                    $draw->setFillColor($font_color);
                    $draw->setFont(resource_path('assets/fonts/AgencyFB-Bold.ttf'));
                    $draw->setFontSize($folder_params['font-size']);

                    // Create text
                    $image->annotateImage($draw, $folder_params['x'], $folder_params['y'], 0, $trabalho_slug);

                    if(!is_dir($path_imagick)) {
                        mkdir($path_imagick, 0777, true);
                    }

                    $image->writeImage($path_imagick . '/' . $i . '.jpg');
                }

                $zip = new ZipArchive();

                $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY);

                foreach($files as $file) {
                    // Skip directories (they would be added automatically)
                    if(!$file->isDir()) {
                        // Get real and relative path for current file
                        $file_path = $file->getRealPath();
                        $relative_path = explode($path . '/', $file_path);

                        // Add current file to archive
                        $zip->addFile($file_path, $relative_path[1]);
                    }
                }

                $zip->close();
            }

            // Remove files (not zip)
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
            }
            rmdir($path);
        }

        return json_encode(['url' =>  '/material-divulgacao/' . $user_id . '/' . $trabalho_slug . '_' . $size . '.zip']);
    }
}
