<?php

require_once __DIR__ . '/../src/core/Reponse.php';
require_once __DIR__ . '/../src/controllers/ChambreController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/core/Csrf.php';

header('Content-Type: application/json; charset=utf-8');

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$methode = $_SERVER['REQUEST_METHOD'];

if ($methode === 'GET' && $chemin === '/api/csrf') {
    Reponse::json(['jeton' => Csrf::jeton()]);
}

if ($methode !== 'GET') {
    Csrf::verifier();
}

if ($methode === 'GET' && $chemin === '/api/chambres') {
    ChambreController::liste();
}

if ($methode === 'GET' && preg_match('#^/api/chambres/([0-9]+)$#', $chemin, $morceaux)) {
    ChambreController::detail((int) $morceaux[1]);
}

if ($methode === 'POST' && $chemin === '/api/inscription') {
    AuthController::inscription();
}

if ($methode === 'POST' && $chemin === '/api/connexion') {
    AuthController::connexion();
}

if ($methode === 'POST' && $chemin === '/api/deconnexion') {
    AuthController::deconnexion();
}

if ($methode === 'GET' && $chemin === '/api/moi') {
    AuthController::moi();
}

Reponse::json(['erreur' => 'Adresse introuvable'], 404);
