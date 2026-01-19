<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('uploadImage')) {
    function uploadImage($file, $path)
    {
        $file_name = $file->hashName();
        Storage::disk('public')->putFileAs($path, $file, $file_name);

        return 'storage/' . $path . '/' . $file_name;
    }
}

/**
 * image delete
 *
 * @param  string  $image
 * @return void
 */
if (! function_exists('deleteFile')) {
    function deleteFile(?string $image)
    {
        $imageExists = file_exists($image);

        if ($imageExists) {
            // if ($image !== 'backend/image/default.png') {
            @unlink($image);
            // }
        }
    }
}