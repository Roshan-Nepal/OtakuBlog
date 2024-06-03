<?php
session_start();
require_once '../functions/helpers.php';
require_once '../functions/pdo_connection.php';

if (!isset($_SESSION['user'])) {
    redirect(url('auth/login.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    $query = "INSERT INTO replies (review_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
    $statement = $pdo->prepare($query);
    $statement->execute([$review_id, $user_id, $comment]);

    // Redirect back to the detail page
    redirect('detail.php?post_id=' . $post_id);
}
?>
