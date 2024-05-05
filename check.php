<?php
require 'vendor/autoload.php'; // Include Slim framework

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/checkPhotoshop', function (Request $request, Response $response, $args) {
    $ids = $request->getQueryParams()['ids'];
    $timestamp = $request->getQueryParams()['timestamp'];

    // Perform input validation if needed
    
    try {
        $req = file_get_contents("https://graph.facebook.com/{$ids}/picture?type=normal");
    } catch (Exception $e) {
        return $response->withStatus(500)->write("Network error");
    }

    if ($req !== false) {
        // Perform image analysis here to check for Photoshop edits
        if (strpos($req, 'Photoshop') !== false) {
            return $response->withStatus(200)->write('Active');
        } else {
            return $response->withStatus(200)->write('Locked');
        }
    } else {
        return $response->withStatus(500)->write("Network error");
    }
});

$app->run();
?>
￼Enter
