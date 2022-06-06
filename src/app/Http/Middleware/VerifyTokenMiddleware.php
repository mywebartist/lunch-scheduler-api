<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class VerifyTokenMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('x-apikey');
        if($key){
            try {
                $key = Crypt::decrypt($key);
//                $user = User::whereEmail($key);
//                if($user){
//                    return $next($request);
//                }
            }catch (\Exception $e){
                $response = [
                    'status_code' => 0,
                    'message' => ' login key is invalid'
                ];
                return response()->json($response, 413);
            };

        }else{
            $response = [
                'status_code' => 0,
                'message' => 'login key is required'
            ];
            return response()->json($response, 413);
        }


        return $next($request);
    }
}
