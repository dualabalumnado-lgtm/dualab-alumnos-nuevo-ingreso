<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Evento extends Model
{
    protected $fillable = [
        'titulo',
        'inicio',
        'color',
        'descripcion' 
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}