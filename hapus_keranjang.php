<?php
session_start();

if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];

    // Hapus produk dari session keranjang
    if (isset($_SESSION['keranjang'][$id_produk])) {
        unset($_SESSION['keranjang'][$id_produk]);
    }
}

// Redirect kembali ke keranjang
header('Location: keranjang.php');
exit();
?>
