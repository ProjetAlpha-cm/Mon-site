<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $newPoint = json_decode($json, true);

    if (!isset($newPoint['x']) || !isset($newPoint['y']) || !isset($newPoint['url'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
        exit;
    }

    $file = 'points.json';
    $points = [];

    if (file_exists($file)) {
        $existing = file_get_contents($file);
        $points = json_decode($existing, true);
        if (!$points) $points = [];
    }

    $points[] = $newPoint;

    if (file_put_contents($file, json_encode($points, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Impossible d\'enregistrer le point']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
?>