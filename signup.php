<?php
require_once 'config.php';

// If already logged in, go to dashboard
if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!preg_match('/\S+@\S+\.com$/', $email)) {
        $errors['email'] = 'Please enter a valid email ending in .com';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }
    
    // If no errors, create account
    if (empty($errors)) {
        $_SESSION['ticketapp_session'] = 'fake-token-12345';
        $_SESSION['user'] = ['email' => $email, 'name' => $name];
        redirect('/dashboard.php');
    }
    
    $old['name'] = $name;
    $old['email'] = $email;
}

echo $twig->render('signup.twig', [
    'errors' => $errors,
    'old' => $old
]);
?>