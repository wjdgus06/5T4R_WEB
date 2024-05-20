<?php
require __DIR__ . '/../vendor/autoload.php';

/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 *     description="API documentation for my project"
 * )
 */

/**
 * @OA\PathItem(
 *     path="/backend/api/users"
 * )
 */

header('Content-Type: application/json');

$openapi = \OpenApi\Generator::scan([__DIR__]);
echo $openapi->toJson();

