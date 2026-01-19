<?php
/**
 * Script de débogage final - affiche exactement ce qui est reçu
 * URL: https://ihm-mutuelle-web.onrender.com/debug-post.php
 */

header('Content-Type: application/json');

echo json_encode([
    'method' => $_SERVER['REQUEST_METHOD'],
    'isAjax' => isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest',
    'post_data' => $_POST,
    'raw_input' => file_get_contents('php://input'),
    'headers' => getallheaders(),
], JSON_PRETTY_PRINT);
