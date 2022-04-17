<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Hash;
use Auth;
use Cookie;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $user = User::create(
            $request->only('name','email') + [
                'password' => Hash::make($request->input('password')),
                'is_admin' => $request->path() === 'api/admin/register' ? 1 : 0
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request){
        // dd('hit');
        if(!Auth::attempt($request->only('email', 'password'))){
            return response([
                'error' => 'Invalid Credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $adminLogin = $request->path() === 'api/admin/register';

        if($adminLogin && !$user->is_admin){
            return response([
                'error' => 'Access Denied'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $scope = $adminLogin ? 1 : 0;

        $jwt = $user->createToken('token', [$scope])->plainTextToken;

        $cookie = cookie('jwt', $jwt, 60*24);
        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function user(Request $request){

        $user = $request->user();

        return new UserResource($user);
    }

    public function logout(){
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }


    public function updateInfo(UpdateInfoRequest $request){

        $user = $request->user();

        $user->update($request->only('name','email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request){

        $user = $request->user();

        if(Hash::check($request->input('previous_password'), $user->password)){
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);
            return response($user, Response::HTTP_ACCEPTED);

        }else{
            return response([
                'error' => 'Incorrect Password'
            ], Response::HTTP_UNAUTHORIZED);
        }


    }
}
