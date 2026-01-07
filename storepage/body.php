<?php
$link = $_GET['link'] ?? 'homes';

switch ($link) {

    case 'homes':
        include_once 'homes.php';
        break;

    case 'table':
        include_once 'tables.php';
        break;

    case 'list':
        include_once 'lists.php';
        break;

    case 'reports':
        include_once 'reports.php';
        break;

    case 'add':
        include_once 'add_stff/addstffs.php';
        break;

    case 'edit':
        include_once 'add_stff/edit_staff.php';
        break;

    default:
        include_once 'homes.php';
}
