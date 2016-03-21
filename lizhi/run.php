<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use \Curl\Curl;

date_default_timezone_set('Asia/Shanghai');

$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);

$logger = new Logger('lizhi');
$logger->pushHandler(new StreamHandler(__DIR__ . '/hunt.log', Logger::INFO));

foreach ($config['ids'] as $index => $id) {
    $response = cheat($id);
    $logger->addInfo($response);
}

function cheat($id)
{
    $url = "http://www.lizhi.fm/audio_cnt";

    $curl = new Curl();

    $curl->get($url, ['id' => $id]);

    return $curl->response;
}
