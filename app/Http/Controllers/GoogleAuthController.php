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
        // $credentials = Editor::where('name',$editor[0])->get();
        // $keys=Editor::find($credentials[0]->id);

        $drive_creds = Drive_credential::all()->first();
        $client = new Client();
        $client->setClientId($drive_creds->client_id);
        $client->setClientSecret($drive_creds->client_secret);
        $client->setRedirectUri(route('google.callback'));
        $client->addScope('https://www.googleapis.com/auth/documents'); // Escopo para acesso ao Google Docs
        $client->addScope('https://www.googleapis.com/auth/drive');
        $client->setApprovalPrompt('force');
        $client->setAccessType('offline'); // Garantir que o token de atualização seja retornado durante o processo de autorização inicial
        $expiration = 2592000; // 30 dias
        $client->setConfig('expires_in', $expiration);

        // Redirecionar para a URL de autorização do Google
        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        // Ler as credenciais do arquivo JSON
        $valorCodificado = request()->cookie('editor');
        $editor=explode('+',base64_decode($valorCodificado));
        // $credentials = Editor::where('name',$editor[0])->get();
        // $keys=Editor::find($credentials[0]->id);

        $drive_creds = Drive_credential::all()->first();
        $client = new Client();
        $client->setClientId($drive_creds->client_id);
        $client->setClientSecret($drive_creds->client_secret);
        $client->setRedirectUri(route('google.callback'));
        $token = null;
        try {
            // Obter o token de acesso usando o código de autorização
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
            // Armazenar o token de acesso e o token de atualização na sessão
            session(['google_access_token' => $token['access_token']]);
            session(['google_refresh_token' => $token['refresh_token']]);
        } catch (\Exception $e) {
            // Lidar com qualquer erro durante o processo de obtenção do token de acesso
            return response('Não foi possivel obter o refresh_token,favor revogar o reconhecimento em sua conta. Error: '.$e,200);
        }

        // Redirecionar para onde quer que você queira ir após o login bem-sucedido
        return redirect()->route('dashboard.gDriveConfig')->with('google_refresh_token', $token['refresh_token']);;
    }
}
