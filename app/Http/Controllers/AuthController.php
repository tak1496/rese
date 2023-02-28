<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function check(Request $request)
    {
        $data = [
            'path' => $request->path,
            'shop_id' => $request->shop_id,
            "reserve" => $request->reserve,
            "date" => $request->date,
            "time" => $request->time,
            "member" => $request->member,
            "res_id" => $request->res_id,
            'text' => ''
        ];
        return view('login', $data);
    }

    public function checkUser(LoginRequest $request)
    {
        $path = $request->query('path');
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            switch($path) {
                case 'form.index':
                    switch(Auth::user()->auth_id) {
                        case 0:
                            return redirect()->route('admin');
                            break;
                        case 1:
                            $data = [
                                'menu' => 0,
                                'shop_id' => Auth::user()->shop_id
                            ];
                            return redirect()->route('owner', $data);
                            break;
                        default:
                            return redirect()->route($path);
                    }
                    break;
                case 'reserve':
                    $data = [
                        'shop_id' => $request->query('shop_id'),
                        "user_id" => Auth::id(),
                        "reserve" => $request->query('reserve'),
                        "date" => $request->query('date'),
                        "time" => $request->query('time'),
                        "member" => $request->query('member'),
                        "path" => "form.index",
                    ];
                    return redirect()->route('detail', $data);
                    break;
                case 'owner':
                    $data = [
                        'shop_id' => Auth::user()->shop_id,
                        "menu" => $request->query('menu'),
                    ];
                    return redirect()->route('owner', $data);
                    break;
                case 'owner.qr':
                    $data = [
                        'res_id' => $request->query('res_id'),
                    ];
                    return redirect()->route('owner.qr', $data);
                    break;
            }
        } else {
            $data = [
                'text' => '※登録されていないユーザーです',
                'path' => $path,
                'shop_id' => $request->query('shop_id'),
                "reserve" => $request->query('reserve'),
                "date" => $request->query('date'),
                "time" => $request->query('time'),
                "member" => $request->query('member'),
                "res_id" => $request->query('res_id'),
            ];
            return view('login', $data);
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('form.index');
    }

    public function register(Request $request)
    {
        $data = [
            'path' => $request->path,
            'shop_id' => $request->shop_id,
            'shop_password' => $request->shop_password,
            'menu' => $request->query('menu'),
            'reserve' => $request->query('reserve '),
            'date' => $request->query('date'),
            'time' => $request->query('time'),
            'member' => $request->query('member'),
            'text' => '',
        ];
        return view('register', $data);
    }

    public function thanks() {
        return view('thanks');
    }
}
