<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;

class AdminController extends Controller
{
    public function index(Request $request) {
        $form = [
            'sel_area' => $request->sel_area,
            'sel_genre' => $request->sel_genre,
            'tx_search' => $request->tx_search
        ];

        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();

        $query = Shop::query();
        if ($request->sel_area !== null) {
            $query->where('area_id', $request->sel_area);
        }

        if ($request->sel_genre !== null) {
            $query->where('genre_id', $request->sel_genre);
        }

        if ($request->tx_search !== null) {
            $query->where('name', 'like', '%' . $request->tx_search . '%');
        }

        $shops = $query->Paginate(15);

        $shop_data = '';
        if($request->kbn === 'new_record') {
            $shop_data = Shop::where('id', $request->shop_id)->get();
        }

        $data = [
            'user' => $user,
            'shops' => $shops,
            'areas' => $areas,
            'genres' => $genres,
            'shop_data' => $shop_data,
            'form' => $form,
        ];

        return view('admin', $data);
    }

    public function register(AdminRequest $request) {
        $file_name = '';
        $strKBN='';
        $shop_id='';

        if($request->photo!=null) {
            $file_name = $request->photo->getClientOriginalName();
        }
        
        if($request->id === null) {
            $form = [
                'name' => $request->name,
                'area_id' => $request->area,
                'genre_id' => $request->genre,
                'overview' => $request->overview,
                'photo' => $file_name,
                'manager' => $request->manager,
                'email' => $request->email,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'tel' => $request->tel,
                'display' => $request->display,
                'password' => uniqid(),
            ];
            $data = Shop::create($form);
            $strKBN = 'new_record';
            $shop_id = $data->id;
            $dir = '/img/shop_' . $shop_id;
        } else {
            $form = [
                'name' => $request->name,
                'area_id' => $request->area,
                'genre_id' => $request->genre,
                'overview' => $request->overview,
                'photo' => $file_name,
                'manager' => $request->manager,
                'email' => $request->email,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'tel' => $request->tel,
                'display' => $request->display,
            ];
            Shop::where('id', $request->id)->update($form);
            $dir = '/img/shop_' . $request->id;
        }

        if($file_name!=='') {
            $request->file('photo')->storeAs('public/' . $dir, $file_name, 's3');

            //$path = Storage::disk('s3')->put('/' . $dir, $file_name, 'public');
        }

        $subject = '';
        switch($strKBN){
            case 'new_record':
                $subject = 'Rese店舗登録のお知らせ';
                break;
        }

        $data = [
            'kbn' => $strKBN,
            'shop_id' => $shop_id,
            'subject' => $subject,
        ];

        return redirect()->route('admin', $data);
    }
}
