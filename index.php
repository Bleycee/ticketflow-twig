<?php
require_once 'config.php';

// If already logged in, go to dashboard
if (isLoggedIn()) {
    redirect('/dashboard.php');
}

// Render the landing page
echo $twig->render('landing.twig');
?>