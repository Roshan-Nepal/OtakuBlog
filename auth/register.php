<?php
require_once '../functions/helpers.php';
require_once '../functions/pdo_connection.php';

$error = '';
if (isset($_POST['email']) && $_POST['email'] !== ''
    && isset($_POST['first_name']) && $_POST['first_name'] !== ''
    && isset($_POST['last_name']) && $_POST['last_name'] !== ''
    && isset($_POST['password']) && $_POST['password'] !== ''
    && isset($_POST['confirm']) && $_POST['confirm'] !== '') {

    if ($_POST['password'] === $_POST['confirm']) {
        if (strlen($_POST['password']) > 5) {
            $query = "SELECT * FROM users WHERE email = ?;";
            $statement = $pdo->prepare($query);
            $statement->execute([$_POST['email']]);
            $user = $statement->fetch();
            if ($user === false) {
                $query = "INSERT INTO users SET email = ?, first_name = ?, last_name = ?, password = ?, role = 'user', created_at = NOW();";
                $statement = $pdo->prepare($query);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $statement->execute([$_POST['email'], $_POST['first_name'], $_POST['last_name'], $password]);
                redirect('auth/login.php');
            } else {
                $error = 'This email already exists';
            }
        } else {
            $error = 'Password must be more than 5 characters';
        }
    } else {
        $error = 'Password does not match the confirm';
    }
} else {
    if (!empty($_POST))
        $error = 'All fields are required';
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
    <link rel="stylesheet" href="<?= asset('assets/css/style1.css') ?>" media="all" type="text/css">
</head>

<body>
    <section id="app">
        <?php require_once '../panel/layouts/top-nav-login.php' ?>
        <section style="height: 100vh; background-color: #5B5B5B;" class="d-flex justify-content-center align-items-center">
            <section class="form-box">
                <h1 class="bg-warning rounded-top px-2 mb-0 py-3 h5">Register with Us</h1>
                <section class="bg-light my-0 px-2">
                    <small class="text-danger"><?php if ($error !== '') echo $error; ?></small>
                </section>
                <form class="pt-3 pb-1 px-2 bg-light rounded-bottom" action="<?= url('auth/register.php') ?>" method="post">
                    <section class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="email ...">
                    </section>
                    <section class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first_name ...">
                    </section>
                    <section class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last_name ...">
                    </section>
                    <section class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="password ...">
                    </section>
                    <section class="form-group">
                        <label for="confirm">Confirm</label>
                        <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm ...">
                    </section>
                    <section class="mt-4 mb-2">
                        <input type="submit" class="btn btn-success btn-sm submit-btn" value="register">
                    </section>
                    <a class="login-link" href="<?= url('auth/login.php') ?>">Login</a>
                </form>
            </section>
        </section>

    </section>
    <script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>
