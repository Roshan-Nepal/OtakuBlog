<?php
session_start();

if (!isset($_SESSION['user']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin')) {
    header('Location: ' . url('index.php'));
    exit();
}
?>