<?php

namespace App\ApiClients;

use App\Services\FileService;
use Illuminate\Support\Facades\Http;

class YandexApi{

    private string $authEndpoint;
    private string $apiEndpoint;
    private string $imageEndpoint;
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private FileService $fileService;
    private string $accessKey;

    public function __construct($redirectUri){
        $this->authEndpoint = 'https://oauth.yandex.ru';
        $this->apiEndpoint = 'https://login.yandex.ru';  
        $this->imageEndpoint = 'https://avatars.yandex.net/get-yapic';
        $this->clientId = env('YANDEX_CLIENT_ID');
        $this->clientSecret = env('YANDEX_CLIENT_SECRET');
        $this->redirectUri = $redirectUri; 
        $this->fileService = new FileService();   
        $this->accessKey = 'yandexAccess'; 
    }

    public function authUri(){
        return $this->authEndpoint.'/authorize'.
                    '?client_id='.$this->clientId.
                    '&redirect_uri='.$this->redirectUri.
                    '&response_type=code'.
                    '&state=123';
    }

    public function authorize($code){
        $response = Http::asForm()
                        ->post($this->authEndpoint.'/token', [
                            'code' => $code,
                            'client_id' => $this->clientId,
                            'client_secret' => $this->clientSecret,
                            'grant_type' => 'authorization_code'
                        ]);
        $access = json_decode($response->body(), true);
        session()->put('yandexAccess', $access);
        session()->save();
        return true;
    }

    public function apiCall($query){
        $access = session($this->accessKey);
        $response = Http::withHeaders([
                        'Authorization' => 'OAuth  '. $access['access_token']
                    ])
                    ->get($this->apiEndpoint.$query);
        return json_decode($response->body(), true);
    }

    public function getProfile(){
        $yandexProfile = $this->apiCall('/info'); 

        // create entity
        $profile = [
            'email' => $yandexProfile['default_email'],
            'first_name' => $yandexProfile['first_name'],
            'last_name' => $yandexProfile['last_name']
        ];

        // profile avatar
        if (isset($yandexProfile['default_avatar_id'])){
            $content = file_get_contents($this->imageEndpoint.'/'.$yandexProfile['default_avatar_id'].'/islands-200');
            $profile['image'] = $this->fileService->save($profile['first_name'].' '.$profile['last_name'], $content);
        }

        return $profile;
    }
    
}