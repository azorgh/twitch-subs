<?php

require __DIR__ . '/vendor/autoload.php';

/**
 * Chargement des variables d'environnements (.env)
 */
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$twitch_client_id = getenv("TWITCH_CLIENT_ID");
$twitch_secret = getenv("TWITCH_SECRET");
$access_token = null;
$refresh_token = getenv('TWITCH_REFRESH_TOKEN');

/**
 * Avant de commencer, on force le refresh du token afin d'être sur de la validité de celui-ci
 */
$token_client = new \GuzzleHttp\Client(['base_uri' => 'https://id.twitch.tv/oauth2/']);
$token_request= $token_client->request('POST', 'token', [
    'query' => [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'client_id' => $twitch_client_id,
        'client_secret' => $twitch_secret
    ]
]);
$token_response = json_decode($token_request->getBody());
$access_token = $token_response->access_token;

/**
 * Création du client de requête sur l'API Twitch
 */
$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://api.twitch.tv/kraken/',
    'headers' => [
        'Authorization' => 'OAuth ' . $access_token,
        'Accept' => 'application/vnd.twitchtv.v5+json',
        'Client-ID' => $twitch_client_id,
    ],
]);

/**
 * On récupère le channel ID via le OAuth Token
 */
$channel_request = $client->request('GET', 'channel');
$channel_result = json_decode($channel_request->getBody());
$channel_id = $channel_result->_id;

/**
 * On récupère la liste des subs via le channel ID
 */
$subs_request = $client->request('GET', 'channels/' . $channel_id . '/subscriptions');
$subs_results = json_decode($subs_request->getBody());

/**
 * On créé ensuite un tableau avec seulement les infos qui nous interesse
 */
$subs = [];
foreach($subs_results->subscriptions as $key => $sub){
    $subs[] = [
        "username" => $sub->user->display_name,
        "plan" => $sub->sub_plan,
        "logo" => $sub->user->logo
    ];
}

/**
 * On enregistre le fichier en JSON
 */
$fp = fopen('subscribers.json', 'w');
fwrite($fp, json_encode($subs));
fclose($fp);