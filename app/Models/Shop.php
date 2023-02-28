<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'area_id', 'genre_id', 'overview', 'photo', 'manager', 'email', 'post_code', 'address', 'tel', 'display', 'password'];

    public function area()
    {
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }

    public function areas()
    {
        return $this->hasMany('App\Models\Area', 'id', 'area_id');
    }

    public function genre()
    {
        return $this->hasOne('App\Models\Genre', 'id', 'genre_id');
    }

    public function genres()
    {
        return $this->hasMany('App\Models\Genre', 'id', 'genre_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like', 'shop_id', 'id')->where('user_id', Auth::id());
    }

    public function reviews_shop() {
        return $this->hasMany('App\Models\Review', 'shop_id', 'id');
    }

    public function reviews_user()
    {
        return $this->hasMany('App\Models\Review', 'shop_id', 'id')->where('user_id', Auth::id());
    }

    public function reviews_list()
    {
        return $this->hasMany('App\Models\Review', 'shop_id', 'id');
    }

    public function reviews()
    {
        return $this->belongsToMany('App\Models\User', 'reviews')->withPivot('point', 'comment');
    }
}
