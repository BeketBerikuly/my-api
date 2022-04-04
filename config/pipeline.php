<?php

use App\Http\Middleware;

/** @var \Framework\Http\Application $app */

$app->pipe(Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware::class);
$app->pipe(Middleware\ResponseLoggerMiddleware::class);
$app->pipe(Framework\Http\Middleware\BodyParamsMiddleware::class);
$app->pipe(Framework\Http\Middleware\RouteMiddleware::class);
$app->pipe(Middleware\EmptyResponseMiddleware::class);
$app->pipe(Framework\Http\Middleware\DispatchMiddleware::class);
