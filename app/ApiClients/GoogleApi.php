<?php

namespace App\ApiClients;

use Illuminate\Support\Facades\Http;
use App\Services\FileService;

class GoogleApi{

    private string $authEndpoint;
    private string $apiEndpoint;
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private FileService $fileService;
    private string $accessKey;

    public function __construct($redirectUri){
        $this->authEndpoint = 'https://accounts.google.com/o/oauth2';
        $this->apiEndpoint = 'https://www.googleapis.com/oauth2/v1';
        $this->clientId = env('GOOGLE_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = $redirectUri;
        $this->fileService = new FileService();
        $this->accessKey = 'yandexAccess'; 
    }

    public function authUri(){
        return $this->authEndpoint.'/v2/auth'.
                    '?response_type=code'.
                    '&access_type=online'.
                    '&client_id='.$this->clientId.
                    '&redirect_uri='.$this->redirectUri.
                    '&scope=email profile'.
                    '&approval_prompt=auto';
    }

    public function authorize($code){
        $response = Http::post($this->authEndpoint.'/token', [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ]);
        $access = json_decode($response->body(), true);
        session()->put($this->accessKey, $access);
        session()->save();
        return true;
    }

    public function apiCall($query){
        $access = session($this->accessKey);
        $response = Http::withHeaders([
                        'Authorization' => 'Bearer '. $access['access_token']
                    ])
                    ->get($this->apiEndpoint.$query);
        return json_decode($response->body(), true);
    }

    public function getProfile(){
        $googleProfile = $this->apiCall('/userinfo?alt=json'); 

        // create entity
        $profile = [
            'email' => $googleProfile['email'],
            'first_name' => $googleProfile['given_name'],
            'last_name' => $googleProfile['family_name']
        ];

        // profile avatar
        if (isset($googleProfile['picture'])){
            $content = file_get_contents($googleProfile['picture']);
            $profile['image'] = $this->fileService->save($profile['first_name'].' '.$profile['last_name'], $content);
        }

        return $profile;
    }
    
}