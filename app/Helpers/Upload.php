<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;

class Upload
{
    public static function upload($f, $file, $name, $target_location)
    {
        if(Request::hasFile($f)) {
            $filename = $name . '.' . $file->getClientOriginalExtension();
            $extension = $file->getClientOriginalExtension();
            $file->move($target_location, $filename);
            return $filename;
        } else {
            return null;
        }
    }
}
