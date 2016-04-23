<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function getURL() {
        return url('uploads') . '/' . $this->image_path;
    }
}
