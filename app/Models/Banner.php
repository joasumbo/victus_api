<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['user_id', 'titulo', 'imagem', 'link'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
