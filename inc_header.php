<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Pinjam</title>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</head>

<style>
    .bg-navbar {
        background-color: #73664F;
        color: #ffffff;
        font-size: 18px;
    }

    .bg-navbar .navbar-brand {
        font-style: italic;
        font-size: 24px;
    }

    .bg-navbar a {
        color: #ffffff;
    }

    .title {
        margin: 1rem auto 1rem;
        text-align: center;
    }

    .back {
        text-decoration: none;
        background-color: #73664F;
        border-radius: 5px;
        width: fit-content;
        color: #ffffff;
    }

    .back:hover {
        background-color: #AD9977;
        color: #000000;
    }

    .all-button {
        color: #000000;
        text-align: center;
        border-radius: 5px;
        background-color: #F2913D;
        margin: auto;
    }

    .all-button:hover {
        background-color: #B86E2E;
        color: #ffffff;
    }

    .bg-edit {
        color: #ffffff;
        text-align: center;
        border-radius: 5px;
        background-color: #73664F;
        margin: auto;
    }

    .bg-edit:hover {
        background-color: #AD9977;
        color: #000000;
    }
</style>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">Admin-Pinjam</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="kendaraan.php">Kendaraan</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="nav-link" href="riwayat.php">Riwayat</a>
                        </li>
                        <li class="nav-item ms-5">
                            <a class="nav-link bg-edit" href="logout.php">Logout>></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>