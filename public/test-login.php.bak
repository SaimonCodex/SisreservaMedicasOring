<?php
// Test directo: cargar Laravel y mostrar login
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/login', 'GET');

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
