<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_id', 'point', 'comment'];

    public function reviews()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
    }
}
