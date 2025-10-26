<?php
require_once 'config.php';

// If already logged in, go to dashboard
if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!preg_match('/\S+@\S+\.com$/', $email)) {
        $errors['email'] = 'Please enter a valid email ending in .com';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }
    
    // If no errors, log them in
    if (empty($errors)) {
        $_SESSION['ticketapp_session'] = 'fake-token-12345';
        $_SESSION['user'] = ['email' => $email, 'name' => 'User'];
        redirect('/dashboard.php');
    }
    
    $old['email'] = $email;
}

echo $twig->render('login.twig', [
    'errors' => $errors,
    'old' => $old
]);
?>