<?php
$link = $_GET['link'] ?? 'home';

switch ($link) {

    case 'table':
        require_once __DIR__ . '/tables_ct.php';
        break;

    case 'mybooking':
        require_once __DIR__ . '/my_reservations.php';
        break;

    default:
        require_once __DIR__ . '/tables_ct.php';
}
