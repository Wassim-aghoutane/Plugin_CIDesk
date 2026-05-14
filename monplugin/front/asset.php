<?php
/**
 * Portable asset proxy for Kubernetes/Ingress deployments.
 *
 * GLPI may route /plugins/monplugin/css and /plugins/monplugin/js as application
 * pages when the public document root does not expose plugin static files.
 * This endpoint serves only whitelisted plugin assets through /front.
 */

$allowed = [
    'css' => [
        'custom.css',
        'dispatcher.css',
        'employe.css',
        'login.css',
        'map-style.css',
        'superadmin.css',
        'technicien.css',
    ],
    'js' => [
        'effects.js',
        'geodash-vue.js',
        'helpdesk-importmap-fix.js',
        'leaflet-logic.js',
        'modal-fix.js',
        'superadmin-central.js',
    ],
];

$type = isset($_GET['type']) ? strtolower((string) $_GET['type']) : '';
$file = isset($_GET['file']) ? (string) $_GET['file'] : '';

if ($file !== '') {
    $file = strtok($file, '?');
    $file = basename($file);
}

if (!isset($allowed[$type]) || !in_array($file, $allowed[$type], true)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Asset not found';
    exit;
}

$path = __DIR__ . '/../' . $type . '/' . $file;
if (!is_readable($path)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Asset not readable';
    exit;
}

$content_type = $type === 'css'
    ? 'text/css; charset=UTF-8'
    : 'application/javascript; charset=UTF-8';

header('Content-Type: ' . $content_type);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=3600');
readfile($path);
