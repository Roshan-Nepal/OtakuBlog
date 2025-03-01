<?php
     require_once 'functions/helpers.php';
     require_once 'functions/pdo_connection.php';
     ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Otaku Blog</title>
<!-- <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'> -->
 <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap.min.css'); ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('assets/css/style.css'); ?>" media="all" type="text/css">
</head>
<body>
<section id="app">

    <?php require_once "layouts/top-nav.php"?>

    <section class="container my-5">
        <!-- Example row of columns -->
        <section class="row">

        <?php
                        $query = "SELECT * FROM posts WHERE status = 10";
                         $statement = $pdo->prepare($query);
                         $statement->execute();
                         $posts = $statement->fetchAll();
                         foreach ($posts as $post) { ?>
           
                <section class="col-md-4">
                    <section class="mb-2 overflow-hidden" style="height: 15rem;"><img class="img-fluid" src="<?= asset($post->image) ?>" style="height:200px; width:400px;" alt=""></section>
                    <h2 class="h5 text-truncate"><?= $post->title ?></h2>
                    <p><?= substr($post->body, 0, 80) ?></p>
                    <p><a class="btn btn-primary" href="<?php echo url('detail.php?post_id=') . $post->id ?>" role="button">View details »</a></p>
                </section>

                <?php } ?>
               
        </section>
    </section>

</section>
<script src="<?php echo asset('assets/js/jquery.min.js') ?>"></script>
<script src="<?php echo asset('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>