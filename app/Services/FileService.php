<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService{

    public function save($name, $content){
        $name = '/uploads/'.Str::slug($name, '-').'.png';
        Storage::disk('public')->put($name, $content);
        return $name;
    }

}