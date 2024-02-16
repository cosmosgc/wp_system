<?php

namespace App\Services;

use GuzzleHttp\Client;

class GptService {

    public function sendRequest($command, $topic) {
        // URL da API do GPT
        $apiUrl = 'https://api.openai.com/v1/chat/completions';



        // Texto para enviar ao GPT para geração
        $requestData = [
            'model' => 'gpt-3.5-turbo', // Atualizado para o modelo GPT-3.5
            
        'messages' => array(
            array(
                'role' => 'system',
                'content' => $command
            ),
            array(
                'role' => 'user',
                'content' => $topic
            ),
        
        ), // Texto de entrada para o modelo GPT
            'max_tokens' => 2000, // Número máximo de tokens a serem gerados na resposta
            'temperature' => 0.7, // Opcional: ajusta a criatividade da resposta
        ];

        // Configuração do cliente GuzzleHttp
        $client = new Client([
            'headers' => [
                'Authorization' => "Bearer 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",
                'Content-Type' => 'application/json',
            ]
        ]);

        // Envia a solicitação POST para a API do GPT
        $response = $client->post($apiUrl, [
            'json' => $requestData,
        ]);

        // Extrai o corpo da resposta como uma string JSON
        $responseData = $response->getBody()->getContents();

        // Decodifica a resposta JSON em um array associativo
        $decodedResponse = json_decode($responseData, true);

        // Retorna a resposta decodificada
        return $decodedResponse;    
    }
}
