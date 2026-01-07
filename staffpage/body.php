<?php
if (isset($_GET['link'])) {
    $link = $_GET['link'];
} else {
    $link = "home";
}
if ($link == 'home') {
    include_once "staff.php";
}
elseif ($link == 'homes') {
    include_once "homes.php";    
}
elseif ($link == 'table') {
    include_once "tables.php";    
}
elseif ($link == 'list') {
    include_once "lists.php";    
}
elseif ($link == 'reports') {
    include_once "reports.php";    
}
?>