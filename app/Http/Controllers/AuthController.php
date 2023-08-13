<?php

namespace App\Http\Controllers;

use App\ApiClients\GoogleApi;
use App\ApiClients\YandexApi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{

    private GoogleApi $googleApi;
    private YandexApi $yandexApi;

    public function __construct(){
        $this->googleApi = new GoogleApi(route('googleCallback'));
        $this->yandexApi = new YandexApi(route('yandexCallback'));
    }

    public function index(){
        return view('pages.auth', [
            'title' => __('auth.title')
        ]);
    }

    public function googleAuth(){
        return redirect()->to($this->googleApi->authUri());
    }

    public function yandexAuth(){
        return redirect()->to($this->yandexApi->authUri());
    }

    public function googleCallback(Request $request){
        $this->googleApi->authorize($request->code);
        $user = $this->googleApi->getProfile();

        $userOrg = User::where('email', $user['email'])->first();
        if ($userOrg==null){
            $user['password'] = Hash::make($user['email']);
            $userOrg = User::create($user);
        }else{
            $userOrg->update($user);
        }

        auth()->loginUsingId($userOrg->id);

        return redirect()->route('home');
    }

    public function yandexCallback(Request $request){
        $this->yandexApi->authorize($request->code);
        $user = $this->yandexApi->getProfile();

        $userOrg = User::where('email', $user['email'])->first();
        if ($userOrg==null){
            $user['password'] = Hash::make($user['email']);
            $userOrg = User::create($user);
        }else{
            $userOrg->update($user);
        }

        auth()->loginUsingId($userOrg->id);

        return redirect()->route('home');
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('auth');
    }

}