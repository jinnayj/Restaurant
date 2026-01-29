<?php
$link = $_GET['link'] ?? 'table';

switch ($link) {

    case 'table':
        include_once 'tables.php';
        break;

    case 'list':
        include_once 'lists.php';
        break;

    case 'edit_lists':
        include_once 'edit_reservation.php';
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
        include_once 'tables.php';
        break;
   
}
