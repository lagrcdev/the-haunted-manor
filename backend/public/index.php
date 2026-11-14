<?php

require_once __DIR__ . '/../src/core/Reponse.php';
require_once __DIR__ . '/../src/controllers/ChambreController.php';

header('Content-Type: application/json; charset=utf-8');

$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$methode = $_SERVER['REQUEST_METHOD'];

if ($methode === 'GET' && $chemin === '/api/chambres') {
    ChambreController::liste();
}

if ($methode === 'GET' && preg_match('#^/api/chambres/([0-9]+)$#', $chemin, $morceaux)) {
    ChambreController::detail((int) $morceaux[1]);
}

Reponse::json(['erreur' => 'Adresse introuvable'], 404);
