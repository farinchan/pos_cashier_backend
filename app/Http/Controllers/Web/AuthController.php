<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function reset($token){
        $cekToken = DB::table('password_reset_tokens')->where('token', '=', $token)->first();
        if (!$cekToken) {
            return redirect()->route('login')->with('error', 'Invalid token');
        }
        $data = [
            'user' => User::where('email', $cekToken->email)->firstOrFail(),
            'token' => $token,
            'title' => 'Reset Password',
            'subTitle' => null,
            'page_id' => null
        ];
        return view('front.auth.reset',  $data);
    }

    public function resetSubmit($token, Request $request){
        $updatePassword = DB::table('password_reset_tokens')->where(['email' => $request->email,'token' => $token])->first();
        if(!$updatePassword){
            return redirect()->route('login')->with('error','Token Invalid');
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->route('reset', $token)->withInput()->withErrors($validator);
        }
        User::where('email', $updatePassword ->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
        return redirect()->route('login')->with('success','Password changed successfully');
    }
}
