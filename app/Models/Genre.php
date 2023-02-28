<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['genre'];

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop', 'genre_id', 'id');
    }
}
