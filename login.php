<?php
session_start();
require_once 'connection.php';

$collectionName = 'admin';
$collection = $database->selectCollection($collectionName);

$username = "";
$password = "";
$err = "";

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == '' or $password == '') {
        $err = "Silakan masukkan semua data";
    } else {
        $document = $collection->findOne(['username' => $username]);

        if (!$document) {
            $err = "Username tidak ditemukan";
        } elseif (!password_verify($password, $document['password'])) {
            $err = "Password tidak sesuai";
        } else {
            $_SESSION['admin_username'] = $username;
            header("location:index.php");
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>Login Admin</title>
</head>

<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    form {
        width: 100%;
        max-width: 330px;
        padding: 15px;
    }

    .form-group {
        margin-top: 15px;
    }

    .btn-login {
        color: #000000;
        text-align: center;
        border-radius: 5px;
        background-color: #F2913D;
        margin-top: 1rem;
    }

    .btn-login:hover {
        background-color: #B86E2E;
        color: #ffffff;
    }
</style>

<body>
    <form action="" method="POST">
        <h2>Login Admin</h2>
        <?php
        if ($err) {
            ?>
            <div class="alert alert-danger">
                <?php echo $err ?>
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username"
                value="<?php echo $username ?>" />
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" />
        </div>
        <button type="submit" class="btn btn-login" name="Login">Login</button>
    </form>
</body>

</html>