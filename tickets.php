<?php
require_once 'config.php';

// Must be logged in
if (!isLoggedIn()) {
    redirect('/login.php');
}

// Initialize tickets if not exists
if (!isset($_SESSION['tickets'])) {
    $_SESSION['tickets'] = [];
}

$tickets = $_SESSION['tickets'];
$showModal = false;
$currentTicket = null;
$formData = [];
$errors = [];
$toast = [];

// Handle actions
$action = $_GET['action'] ?? '';

if ($action === 'create') {
    $showModal = true;
}

if ($action === 'edit' && isset($_GET['id'])) {
    $showModal = true;
    $ticketId = (int)$_GET['id'];
    foreach ($tickets as $ticket) {
        if ($ticket['id'] == $ticketId) {
            $currentTicket = $ticket;
            $formData = $ticket;
            break;
        }
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    $ticketId = (int)$_GET['id'];
    $_SESSION['tickets'] = array_filter($tickets, fn($t) => $t['id'] != $ticketId);
    $_SESSION['tickets'] = array_values($_SESSION['tickets']);
    $_SESSION['toast'] = ['message' => 'Ticket deleted successfully!', 'type' => 'success'];
    redirect('/tickets.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';
    
    if ($postAction === 'create' || $postAction === 'update') {
        $title = trim($_POST['title'] ?? '');
        $status = $_POST['status'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $priority = $_POST['priority'] ?? '';
        
        // Validation
        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }
        if (empty($status)) {
            $errors['status'] = 'Status is required';
        } elseif (!in_array($status, ['open', 'in_progress', 'closed'])) {
            $errors['status'] = 'Status must be: open, in_progress, or closed';
        }
        
        if (empty($errors)) {
            if ($postAction === 'create') {
                $newTicket = [
                    'id' => time(),
                    'title' => $title,
                    'description' => $description,
                    'status' => $status,
                    'priority' => $priority
                ];
                $_SESSION['tickets'][] = $newTicket;
                $_SESSION['toast'] = ['message' => 'Ticket created successfully!', 'type' => 'success'];
            } else {
                $ticketId = (int)$_POST['id'];
                foreach ($_SESSION['tickets'] as $key => $ticket) {
                    if ($ticket['id'] == $ticketId) {
                        $_SESSION['tickets'][$key] = [
                            'id' => $ticketId,
                            'title' => $title,
                            'description' => $description,
                            'status' => $status,
                            'priority' => $priority
                        ];
                        break;
                    }
                }
                $_SESSION['toast'] = ['message' => 'Ticket updated successfully!', 'type' => 'success'];
            }
            redirect('/tickets.php');
        } else {
            $showModal = true;
            $formData = $_POST;
            if ($postAction === 'update') {
                $currentTicket = ['id' => $_POST['id']];
            }
        }
    }
}

// Get toast from session and clear it
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
}

echo $twig->render('tickets.twig', [
    'tickets' => $_SESSION['tickets'],
    'show_modal' => $showModal,
    'current_ticket' => $currentTicket,
    'form_data' => $formData,
    'errors' => $errors,
    'toast' => $toast
]);
?>