<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\OwnerRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reserve;

class OwnerController extends Controller
{
    public function index(Request $request) {
        $menu = $request->menu;

        $param = [
            'name' => $request->name,
            'date1' => $request->date1,
            'date2' => $request->date2,
            'check' => $request->check,
            'situation' => $request->situation,
        ];

        $shop_id = $request->shop_id;
        $auth = Auth::user();
        
        if($auth!==null) {
            if ((int)$request->shop_id !== (int)$auth->shop_id) {
                return view('owner_error');
            }
        }

        if($shop_id===null) {
            return view('owner_error');
        }

        if($menu=="0") {

            $query = Reserve::query()->with('reserves')->where('shop_id', $shop_id);

            if ($param['name'] !== null) {
                $query = $query->whereHas('reserves', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            }

            if( ($param['date1'] != null) && ($param['date2'] != null)) {
                $query = $query->whereBetween('reserve', [$param['date1'].' 00:00:00', $param['date2'].' 23:59:59']);
            } elseif($param['date1'] != null) {
                $query = $query->whereBetween('reserve', [$param['date1'] . ' 00:00:00', $param['date1'] . ' 23:59:59']);
            } elseif ($param['date2'] != null) {
                $query = $query->whereBetween('reserve', [$param['date2'] . ' 00:00:00', $param['date2'] . ' 23:59:59']);
            }

            if ($request->check !== null) {
                $query = $query->where('check', $request->check);
            }

            if ($request->situation !== null) {
                $query = $query->where('situation', $request->situation);
            }

            $query = $query->orderBy("check", "ASC")->orderBy("situation", "ASC");

            $reserves = $query->Paginate(15);

            $data = [
                'user' => $auth,
                'menu' => $menu,
                'reserves' => $reserves,
            ];
        } elseif($menu=="1") {
            $password = $request->password;
            $data = [
                'shop_id' => $shop_id,
                'shop_password' => $password,
                'path' => 'owner',
                'menu' => $menu,
            ];
            
            if ($request->kbn === 'new_shop') {
                $shop = Shop::where([
                    ['id', $shop_id],
                    ['password', $password],
                ])->first();
                
                if ($shop === null) {
                    return view('owner_error');
                }

                // すでにユーザー登録済みの店舗かチェック
                $chk = User::where('shop_id', $shop_id)->first();
                if ($chk === null) {     //ユーザー未登録の場合、ユーザー新規登録へ
                    return redirect()->route('auth.register', $data);
                }
            }

            if ($auth === null) {
                return redirect()->route('auth.login',  $data);
            }

            $shop = Shop::where('id', $shop_id)->first();
            $areas = Area::all();
            $genres = Genre::all();
            $data = [
                'shop' => $shop,
                'user' => $auth,
                'areas' => $areas,
                'genres' => $genres,
                'menu' => $menu,
            ];
        }

        return view('owner', $data);
    }

    public function register(OwnerRequest $request) {
        $file_name = $request->photo->getClientOriginalName();
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

        $request->file('photo')->storeAs('public/' . $dir, $file_name, 's3');

        $data = [
            'shop_id' => $request->id,
            'menu' => $request->menu,
        ];
        return redirect()->route('owner', $data);
    }

    public function check(Request $request) {
        Reserve::find($request->id)->update(['check' => $request->check]);
    }

    public function situation(Request $request)
    {
        Reserve::find($request->id)->update(['situation' => $request->situation]);
    }

    public function qr(Request $request)
    {
        $user = Auth::user();
        $reserve = Reserve::with('shop')->with('reserves')->where('id', $request->res_id)->first();
        $data = [
            'user' => $user,
            'reserve' => $reserve,
        ];

        if ($reserve === null) {
            $data['message'] = '該当する予約は見つかりませんでした';
            return view('owner_reserve_error', $data);
        } elseif ($user->auth_id !== 1) {
            $data['message'] = '管理者アカウントでログインして下さい';
            return view('owner_reserve_error', $data);
        } elseif ($reserve->shop_id != $user->shop_id) {
            $data['message'] = '該当する予約は見つかりませんでした';
            return view('owner_reserve_error', $data);
        } else {
            $data['reserve'] = $reserve;
            return view('owner_reserve', $data);
        }
    }


    public function owner_reserve(Request $request)
    {
        $data = [
            'check' => 1,
            'situation' => 3
        ];
        Reserve::where('id',$request->id)->update($data);
    }
}
