<?php

session_start();

// Loading composer autoload
require "vendor/autoload.php";

// Loading .env content in $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Generating CRSF token for this session
if (!isset($_SESSION["csrfToken"])) {
    $tokenManager = new CSRFTokenManager();
    $token = $tokenManager->generateCSRFToken();

    $_SESSION["csrfToken"] = $token;
}

// Instantiate the router and handle the get request
$router = new Router;
$router->handleRequest($_GET);
