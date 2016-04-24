<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function getURL() {
        return url('uploads') . '/' . $this->image_path;
    }
    
    public function getArray() {
        return ['title' => ($this->title ? $this->title : 'Untitled'), 'image_path' => $this->getURL()];
    }
}
