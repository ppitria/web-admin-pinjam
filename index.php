<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("location: login.php");
    exit();
}
include("inc_header.php") ?>


<div class="container">
    <style>
        h1,
        p {
            text-align: center;
        }
    </style>
    <h1>Welcome</h1>
    <p>Admin pengelola peminjaman kendaraan</p>
</div>

<?php include("inc_footer.php") ?>