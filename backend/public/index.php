<?php

require_once __DIR__ . '/../src/models/ChambreModel.php';

header('Content-Type: application/json; charset=utf-8');

$chambres = ChambreModel::getAll();

echo json_encode($chambres);

