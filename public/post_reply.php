<?php
require_once '../functions/helpers.php';
require_once '../functions/pdo_connection.php';

session_start(); // Make sure to start the session to use session variables

if (!isset($_SESSION['user'])) {
    redirect(url('auth/login.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    $query = "INSERT INTO replies (review_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
    $statement = $pdo->prepare($query);
    $statement->execute([$review_id, $user_id, $comment]);

    // Get the post_id to redirect back to the detail page
    $query = "SELECT post_id FROM reviews WHERE id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$review_id]);
    $review = $statement->fetch();

    // Redirect back to the detail page
    redirect('detail.php?post_id=' . $review->post_id);
}
?>
