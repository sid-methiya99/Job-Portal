<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'isLoggedIn' => isLoggedIn()
    ]);
}
?> 