<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Like;
use App\Models\Reserve;
use App\Http\Requests\ReserveRequest;
use App\Models\Review;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $form = [
            'sel_area' => $request->sel_area,
            'sel_genre' => $request->sel_genre,
            'tx_search' => $request->tx_search
        ];

        $query = Shop::query()->where('display',1);
        if ($request->sel_area !== null) {
            $query->where('area_id', $request->sel_area);
        }

        if ($request->sel_genre !== null) {
            $query->where('genre_id', $request->sel_genre);
        }

        if ($request->tx_search !== null) {
            $query->where('name', 'like', '%' . $request->tx_search . '%');
        }

        if (Auth::id()) {
            $query = $query->with('likes');
        }

        $query = $query->withCount('reviews_shop')->withAvg('reviews_shop', 'point');
        
        $shops = $query->get();
        $areas = Shop::groupBy('area_id')->with('area')->where('display',1)->orderBy('area_id')->get('area_id');
        $genres = Genre::all();

        return view('index', [
            'shops' => $shops,
            'areas' => $areas,
            'genres' => $genres,
            'form' => $form,
            'user' => $user
        ]);
    }

    public function like(Request $request) {
        if($request->process==='insert') {
            $data = [
                'shop_id' => $request->shop_id,
                'user_id' => $request->user_id
            ];
            Like::create($data);
        } elseif($request->process==='del') {
            Like::where('shop_id', $request->shop_id)->where('user_id', $request->user_id)->delete();
        }
    }

    public function detail(Request $request) {
        $item = Shop::find($request->shop_id);
        $user = Auth::user();
        //$reviews = Shop::with('reviews')->where('id', $request->shop_id)->get();
        $reviews = Review::with('reviews')->where('shop_id', $request->shop_id)->get();

        $data = [
            'shop' => $item,
            'user' => $user,
            'path' => $request->path,
            'reviews' => $reviews,
            'reserve' => $request->query('reserve'),
            'date' => $request->query('date'),
            'time' => $request->query('time'),
            'member' => $request->query('member'),
        ];

        return view('detail', $data);
    }

    public function reserve(ReserveRequest $request) {
        $form = [
            'reserve' => $request->reserve,
            'member' => $request->member,
            'shop_id' => $request->shop_id,
            'user_id' => $request->user_id,
            'check' => 0,
            'situation' => 1,
            'price' => 0,
        ];

        Reserve::create($form);
        $user = Auth::user();
        $data = [
            'user' => $user
        ];
        return view('done', $data);
    }

    public function reserve_get(Request $request)
    {
        $user = Auth::user();
        $data = [
            'shop_id' => $request->shop_id,
            'user_id' => $request->user_id,
            'reserve' => $request->reserve,
            'member' => $request->member,
            'user' => $user,
            'check' => 0,
            'situation' => 1,
            'price' => 0,
        ];
        Reserve::create($data);
        return view('done', $data);
    }

    public function mypage() {
        $user = Auth::user();
        $reserves = Reserve::with('shop')->where([
            ['user_id', auth::id()],
            ['situation','<>', 0],
        ])->whereDate
        ('reserve', '>=', today())->get();
        $shops = Shop::with('likes')->has('likes')->with('reviews_user')->get();
        $items = [
            'user' => $user,
            'reserves' => $reserves,
            'shops' => $shops
        ];
        return view('mypage', $items);
    }

    public function res_del(Request $request) {
        $data = [
            'check' => 0,
            'situation' => 0,
            'price' => 0,
        ];
        Reserve::find($request->id)->update($data);
        return redirect()->route('mypage');
    }

    public function res_change(Request $request) {
        Reserve::where('id', $request->id)->update([
            'reserve' => $request->reserve,
            'member' => $request->member,
            'check' => 0,
            'situation' => 2,
            'price' => 0,
        ]);
        return redirect()->route('mypage');
    }

    public function review(Request $request) {
        if($request->rev_id !=null) {
            $data = [
                'point' => $request->point,
                'comment' => $request->comment,
            ];
            Review::where('id', $request->rev_id)->update($data);
        } else {
            $data = [
                'user_id' => $request->rev_user_id,
                'shop_id' => $request->rev_shop_id,
                'point' => $request->point,
                'comment' => $request->comment,
            ];
            Review::create($data);
        }
        return redirect()->route('mypage');
    }

    /*
    public function login() {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }
    */
}
