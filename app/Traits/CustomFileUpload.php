<?php

namespace App\Traits;
use Str;

trait CustomFileUpload {

    public function uploadFile($image, $path)
    {
        // dd($image);
        $image_name = uniqid( Str::random( 12 ) ) . '.' . $image->getClientOriginalExtension();
        $image->move( $path, $image_name );
        return $image_name;
    }

    public function deleteFile($image, $path)
    {
        $image_path = $path. $image;
        if ( file_exists( $image_path ) ) {
            @unlink( $image_path );
        }
    }
}
