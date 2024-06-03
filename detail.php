<?php
require_once 'functions/helpers.php';
require_once 'functions/pdo_connection.php';

$post_id = $_GET['post_id'];

// Fetch post details
$query = "SELECT posts.*, categories.name AS category_name FROM posts JOIN categories ON posts.cat_id = categories.id WHERE posts.id = ? AND posts.status = 10";
$statement = $pdo->prepare($query);
$statement->execute([$post_id]);
$post = $statement->fetch();

if ($post !== false) {
    // Fetch reviews for the post
    $query = "SELECT reviews.*, users.first_name, users.last_name FROM reviews 
              JOIN users ON reviews.user_id = users.id WHERE post_id = ? ORDER BY created_at DESC";
    $statement = $pdo->prepare($query);
    $statement->execute([$post_id]);
    $reviews = $statement->fetchAll();

    // Calculate aggregate rating
    $totalReviews = count($reviews);
    $aggregateRating = $totalReviews ? array_sum(array_column($reviews, 'rating')) / $totalReviews : 0;

    // Fetch replies for each review
    $replies = [];
    foreach ($reviews as $review) {
        $query = "SELECT replies.*, users.first_name, users.last_name FROM replies 
                  JOIN users ON replies.user_id = users.id WHERE review_id = ? ORDER BY created_at ASC";
        $statement = $pdo->prepare($query);
        $statement->execute([$review->id]);
        $replies[$review->id] = $statement->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Otaku Blog</title>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>
<body>
<section id="app">
    <?php require_once "layouts/top-nav.php"?>

    <section class="container my-5">
        
        <section class="row">
            <section class="col-md-12">

            <?php 

             //check for exist post 
        $query = "SELECT posts.*, categories.name AS category_name FROM posts JOIN categories ON posts.cat_id = categories.id WHERE posts.id = ? AND posts.status = 10 ;";
        $statement = $pdo->prepare($query);
        $statement->execute([$_GET['post_id']]);
        $post = $statement->fetch();
        if ($post !== false) {
            ?>

                <h1><?= $post->title ?></h1>
                <h5 class="d-flex justify-content-between align-items-center">
                    <a href="<?= url('category.php?cat_id=') . $post->cat_id ?>"><?= $post->category_name ?></a>
                    <span class="date-time"><?= $post->created_at ?></span>
                </h5>
                <article class="bg-article p-3"><img class="float-right mb-2 ml-2" style="width: 18rem;" src="<?= asset($post->image) ?>" alt=""><?= $post->body ?></article>
                <?php
        } else{ ?>
            
                    <section>post not found!</section>
                    <?php } ?>
             
            </section>
        </section>
    </section>

</section>

</body>
</html>