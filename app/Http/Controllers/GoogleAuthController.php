<?php

namespace App\Http\Controllers;

use App\Models\Drive_credential;
use App\Models\Editor;
use Illuminate\Http\Request;
use Google\Client;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle($editor)
    {
        // Ler as credenciais do arquivo JSON
        $credentials = Editor::where('name',$editor)->get();

        $client = new Client();
        $client->setClientId($credentials[0]->GoogleCredentials->client_id);
        $client->setClientSecret($credentials[0]->GoogleCredentials->client_secret);
        $client->setRedirectUri(route('google.callback'));
        $client->addScope('https://www.googleapis.com/auth/documents'); // Escopo para acesso ao Google Docs
        $client->setAccessType('offline'); // Garantir que o token de atualização seja retornado durante o processo de autorização inicial

        // Redirecionar para a URL de autorização do Google
        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        // Ler as credenciais do arquivo JSON
        $credentials = Editor::where('name',$request->editor)->get();

        $client = new Client();
        $client->setClientId($credentials[0]->GoogleCredentials->client_id);
        $client->setClientSecret($credentials[0]->GoogleCredentials->client_secret);
        $client->setRedirectUri(route('google.callback'));

        try {
            // Obter o token de acesso usando o código de autorização
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            // Armazenar o token de acesso e o token de atualização na sessão
            session(['google_access_token' => $token['access_token']]);
            session(['google_refresh_token' => $token['refresh_token']]);
        } catch (\Exception $e) {
            // Lidar com qualquer erro durante o processo de obtenção do token de acesso
            return redirect()->route('google.redirect')->with('error', 'Failed to obtain access token: ' . $e->getMessage());
        }

        // Redirecionar para onde quer que você queira ir após o login bem-sucedido
        return redirect()->route('dashboard.DocumentCreated');
    }
}
