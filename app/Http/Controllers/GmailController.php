<?php

namespace App\Http\Controllers;

use Google\Client;
use Google_Client;
use Google\Service\Gmail;
use Google_Service_Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GmailController extends Controller
{
/*     protected $client;

    public function __construct()
    {
        // Crea una instancia del cliente de Google API
        $this->client = new Client();
        $this->client->setApplicationName('nth-circlet-383303');
        $this->client->setAccessType('offline');
        $this->client->setAuthConfig(env('GOOGLE_APPLICATION_CREDENTIALS'));
        $this->client->setScopes([
            Gmail::GMAIL_READONLY
        ]);
    } */

    public function index()
    {
/*         // Autentica con las credenciales de Google
        $accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];
        $this->client->setAccessToken($accessToken);

        // Crea una instancia del servicio Gmail
        $service = new Gmail($this->client);

        // Obtiene los mensajes del usuario
        $messages = $service->users_messages->listUsersMessages('me');

        // Procesa los mensajes
        $result = [];
        foreach ($messages as $message) {
            $message = $service->users_messages->get('me', $message->getId());
            $headers = $message->getPayload()->getHeaders();
            $subject = '';
            $from = '';
            $date = '';
            foreach ($headers as $header) {
                if ($header->getName() == 'Subject') {
                    $subject = $header->getValue();
                }
                if ($header->getName() == 'From') {
                    $from = $header->getValue();
                }
                if ($header->getName() == 'Date') {
                    $date = $header->getValue();
                }
            }
            $result[] = [
                'subject' => $subject,
                'from' => $from,
                'date' => $date
            ];
        } */

        return view('emails');
    }
}
