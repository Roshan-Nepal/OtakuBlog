<?php
session_start();
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    redirect('auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];

    // Get the post_id to redirect back to the detail page
    $query = "SELECT post_id FROM reviews WHERE id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$review_id]);
    $review = $statement->fetch();

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Delete replies associated with the review
        $query = "DELETE FROM replies WHERE review_id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$review_id]);

        // Delete the review
        $query = "DELETE FROM reviews WHERE id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$review_id]);

        // Commit transaction
        $pdo->commit();

        // Redirect back to the detail page
        redirect('detail.php?post_id=' . $review->post_id);
        exit;

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw $e;
    }
}
?>