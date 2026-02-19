<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use Google\Auth\OAuth2;
use Google\Service\FirebaseCloudMessaging;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class GoogleOAuthController extends Controller
{
    public function getAuthToken()
    {
        $client = new Client();
        $client->setAuthConfig(public_path('client_secret.json'));
        $client->addScope(FirebaseCloudMessaging::FIREBASE_MESSAGING);
        // $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('none');

        if (file_exists(public_path('secret_token.json'))) {
            $json_file = file_get_contents(public_path('secret_token.json'));
            if ($json_file) {
                $token_details = json_decode($json_file, true);
                $client->setAccessToken($token_details);
            }
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $access_json_data = $client->getAccessToken();
                Log::info("newAccessToken");
                $data = [];
                foreach ($access_json_data as $key => $value) {
                    $data[$key] = $value;
                }
                file_put_contents(public_path('secret_token.json'), json_encode($data, true));
            } else {
                return $this->redirectToGoogle();
            }
        }
    }

    public function redirectToGoogle()
    {
        $client = new Client();
        $client->setClientId('579935729583-1srcd1bijiikohk05c9e1778o34on04n.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('https://rrk-erp.firebaseapp.com/__/auth/handler');
        $client->addScope(FirebaseCloudMessaging::FIREBASE_MESSAGING);
        // $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        $client->setRedirectUri(route('admin.OAuthResponse'));
        // offline access will give you both an access and refresh token so that
        // your app can refresh the access token without user interaction.
        $client->setAccessType('offline');

        // $client->setApprovalPrompt('force');
        $client->setPrompt('consent');
        $client->setIncludeGrantedScopes(true);   // incremental auth

        $auth_url = $client->createAuthUrl();
        return Redirect::away($auth_url);
    }

    public function OAuthResponse(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(public_path('client_secret.json'));

        $client->addScope(FirebaseCloudMessaging::FIREBASE_MESSAGING);
        // $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);
        $client->setRedirectUri(route('admin.OAuthResponse'));
        $access_json_data = $client->authenticate($_GET['code']);

        $data = [];
        foreach ($access_json_data as $key => $value) {
            $data[$key] = $value;
        }
        file_put_contents(public_path('secret_token.json'), json_encode($data, true));

        $access_token = $_SESSION['access_token'] = $client->getAccessToken();
        $refresh_token = $_SESSION['refresh_token'] = $client->getRefreshToken();
        $client->setAccessToken($access_token);
        $client->fetchAccessTokenWithRefreshToken($refresh_token);
        Log::info("access_token");
        Log::info($access_token);
        return redirect()->route('admin.dashboard');
    }
}
