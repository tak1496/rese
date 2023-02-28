<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $data = [
                'path' => $request->path,
                'shop_id' => $request->shop_id,
                "user_id" => null,
                "reserve" => $request->reserve,
                "date" => $request->res_date,
                "time" => $request->res_time,
                "member" => $request->member,
                "res_id" => $request->res_id,
            ];
            return route('auth.login', $data);
        }
    }
}
