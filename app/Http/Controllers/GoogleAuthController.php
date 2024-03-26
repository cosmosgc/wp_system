<?php

namespace App\Http\Controllers;

use App\Models\Drive_credential;
use App\Models\Editor;
use Illuminate\Http\Request;
use Google\Client;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        // Ler as credenciais do arquivo JSON
        $valorCodificado = request()->cookie('editor');
        $editor=explode('+',base64_decode($valorCodificado));
        $credentials = Editor::where('name',$editor[0])->get();
        $keys=Editor::find($credentials[0]->id);

        $client = new Client();
        $client->setClientId($keys->GoogleCredentials->client_id);
        $client->setClientSecret($keys->GoogleCredentials->client_secret);
        $client->setRedirectUri(route('google.callback'));
        $client->addScope('https://www.googleapis.com/auth/documents'); // Escopo para acesso ao Google Docs
        $client->addScope('https://www.googleapis.com/auth/drive');
        $client->setAccessType('offline'); // Garantir que o token de atualização seja retornado durante o processo de autorização inicial

        // Redirecionar para a URL de autorização do Google
        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        // Ler as credenciais do arquivo JSON
        $valorCodificado = request()->cookie('editor');
        $editor=explode('+',base64_decode($valorCodificado));
        $credentials = Editor::where('name',$editor[0])->get();
        $keys=Editor::find($credentials[0]->id);

        $client = new Client();
        $client->setClientId($keys->GoogleCredentials->client_id);
        $client->setClientSecret($keys->GoogleCredentials->client_secret);
        $client->setRedirectUri(route('google.callback'));

        try {
            // Obter o token de acesso usando o código de autorização
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
            // Armazenar o token de acesso e o token de atualização na sessão
            session(['google_access_token' => $token['access_token']]);
            session(['google_refresh_token' => $token['refresh_token']]);
        } catch (\Exception $e) {
            // Lidar com qualquer erro durante o processo de obtenção do token de acesso
            dd('chegou aqui');
            return redirect()->route('google.redirect')->with('error', 'Failed to obtain access token: ' . $e->getMessage());
        }

        // Redirecionar para onde quer que você queira ir após o login bem-sucedido
        return redirect()->route('dashboard.gDriveConfig');
    }
}
