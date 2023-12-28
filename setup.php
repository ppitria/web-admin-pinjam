<?php
require_once 'connection.php';
$collectionName = 'admin';
$collection = $database->selectCollection($collectionName);

$hashedPassword = password_hash("admin123", PASSWORD_DEFAULT);
$collection->insertOne(['username' => 'admin', 'password' => $hashedPassword]);

echo "Akun admin berhasil dibuat!";
?>