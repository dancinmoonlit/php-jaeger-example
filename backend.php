<?php

use Jaeger\Config;
use OpenTracing\GlobalTracer;

require_once  'vendor/autoload.php';

$config = Config::getInstance();
$tracer = $config->initTrace('backend', '0.0.0.0:6831');
GlobalTracer::set($tracer);


$carrier = getallheaders();
$trace_context = $tracer->extract(OpenTracing\Formats\TEXT_MAP, $carrier);

$span = $tracer->startSpan('http_request', [
    'child_of' => $trace_context
]);

$span->log($carrier);

usleep(100);

$child_span = $tracer->startSpan('user:get_list:mysql_query', [
    'child_of' => $span,
]);

usleep(1000);

$child_span->finish();

$span->finish();


register_shutdown_function(function () use ($tracer) {
    GlobalTracer::get()->flush();
});

