<?php
session_start();

if (isset($_POST['id_produk']) && isset($_POST['jumlah'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    // Perbarui jumlah produk di session keranjang
    if (isset($_SESSION['keranjang'][$id_produk])) {
        $_SESSION['keranjang'][$id_produk]['jumlah'] = $jumlah;
    }
}

// Redirect kembali ke keranjang
header('Location: keranjang.php');
exit();
?>
