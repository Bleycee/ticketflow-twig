<?php
// config.php - Sets up Twig and sessions

require_once __DIR__ . '/vendor/autoload.php';

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);

// Start session for login/logout
session_start();

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['ticketapp_session']);
}

// Helper function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}
?>