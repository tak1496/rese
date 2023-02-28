<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;

    protected $fillable = ['reserve', 'member', 'user_id', 'shop_id', 'check', 'situation', 'price'];
    protected $dates = ['reserve'];

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    public function reserves()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
    }
}
