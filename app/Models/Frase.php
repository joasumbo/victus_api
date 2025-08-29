<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frase extends Model
{
    protected $fillable = ['user_id', 'frase'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
