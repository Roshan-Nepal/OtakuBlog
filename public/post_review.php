<?php
require_once '../functions/helpers.php';
require_once '../functions/pdo_connection.php';

session_start(); // Make sure to start the session to use session variables

if (!isset($_SESSION['user'])) {
    redirect(url('auth/login.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $query = "INSERT INTO reviews (post_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
    $statement = $pdo->prepare($query);
    $statement->execute([$post_id, $user_id, $rating, $comment]);

    // Redirect back to the detail page
    redirect('detail.php?post_id=' . $post_id);
}
?>
