<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use \Curl\Curl;

date_default_timezone_set('Asia/Shanghai');

$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);

$logger = new Logger('ximalaya');
$logger->pushHandler(new StreamHandler(__DIR__ . '/hunt.log', Logger::INFO));

foreach ($config['sounds'] as $index => $value) {
    // repeat default to 1.
    if (!isset($value['repeat'])) {
        $value['repeat'] = 1;
    }

    repeat($value['repeat'], function () use ($value, $logger) {
        $response = cheat($value['user'], $value['sound']);
        $logger->addInfo($response);
    }, 1000);
}

function cheat($userId, $soundId)
{
    $url     = "http://m.ximalaya.com/tracks/$soundId/play";
    $referer = "http://m.ximalaya.com/$userId/sound/$soundId";
    $agent   = "Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1";

    $curl = new Curl();
    $curl->setReferer($referer);
    $curl->setUserAgent($agent);
    $curl->post($url, ['duration' => rand(200, 1000)]);

    return $curl->response;
}

function repeat($times, Closure $callback, $interval = 0)
{
    if (is_null($callback)) {
        return;
    }
    for ($i = 0; $i < $times; $i++) {
        call_user_func($callback, $i);
        usleep($interval * 1000);
    }
}
