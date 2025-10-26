<?php
require_once 'config.php';

// Must be logged in
if (!isLoggedIn()) {
    redirect('/login.php');
}

// Initialize tickets in session if not exists
if (!isset($_SESSION['tickets'])) {
    $_SESSION['tickets'] = [
        [
            'id' => 1,
            'title' => 'Fix login bug',
            'description' => 'Users are unable to login with their credentials on mobile devices',
            'status' => 'open',
            'priority' => 'high'
        ],
        [
            'id' => 2,
            'title' => 'Update dashboard UI',
            'description' => 'Redesign the dashboard to match new brand guidelines',
            'status' => 'in_progress',
            'priority' => 'medium'
        ],
        [
            'id' => 3,
            'title' => 'Add export feature',
            'description' => 'Allow users to export ticket data to CSV format',
            'status' => 'closed',
            'priority' => 'low'
        ],
        [
            'id' => 4,
            'title' => 'Performance optimization',
            'description' => 'Improve page load time and reduce API response time',
            'status' => 'in_progress',
            'priority' => 'high'
        ]
    ];
}

// Calculate stats
$tickets = $_SESSION['tickets'];
$stats = [
    'total' => count($tickets),
    'open' => count(array_filter($tickets, fn($t) => $t['status'] === 'open')),
    'in_progress' => count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress')),
    'resolved' => count(array_filter($tickets, fn($t) => $t['status'] === 'closed'))
];

echo $twig->render('dashboard.twig', [
    'stats' => $stats
]);
?>