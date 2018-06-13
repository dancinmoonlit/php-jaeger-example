<?php

use GuzzleHttp\Client;
use Jaeger\Config;
use OpenTracing\GlobalTracer;

require_once  'vendor/autoload.php';

$config = Config::getInstance();
$tracer = $config->initTrace('frontend', '0.0.0.0:6831');

GlobalTracer::set($tracer);

$span = $tracer->startSpan('Frontend root span');
$span->addBaggageItem("app_version", "1.30");
$span->log(
    [
        'method' => 'POST',
        'msg' => 'Making http request to backend service'
    ]
);

$headers = [];
$tracer->inject($span->getContext(), OpenTracing\Formats\TEXT_MAP, $headers);

//call backend service
$request = new \GuzzleHttp\Psr7\Request('POST', 'localhost:9500', $headers);
$client = new Client();
$response = $client->send($request);

$span->log(
    [
        'msg' => 'Http request finished'
    ]
);

$span->finish();

echo "DONE\n";

register_shutdown_function(function () use ($tracer) {
    GlobalTracer::get()->flush();
});

