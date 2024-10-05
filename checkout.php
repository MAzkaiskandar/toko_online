<?php
session_start();
include "koneksi.php";

// Periksa apakah keranjang tidak kosong
if (!empty($_SESSION["keranjang"])) {
    // Mendapatkan data dari session keranjang
    $cart = $_SESSION["keranjang"];

    // Hitung total belanja
    $total_belanja = 0;
    foreach ($cart as $item) {
        $total_belanja += $item["harga"] * $item["jumlah"];
    }

    // Proses checkout
    $id_pelanggan = $_SESSION['id_pelanggan']; // Ambil id pelanggan dari session login
    $id_petugas = $_SESSION['id_petugas']; // Ambil id petugas dari session login
    $tgl_transaksi = date('Y-m-d');

    // Masukkan data transaksi ke tabel transaksi
    $query_transaksi = "INSERT INTO transaksi (id_pelanggan, id_petugas, tgl_transaksi) 
                        VALUES ('$id_pelanggan', '$id_petugas', '$tgl_transaksi')";
    
    mysqli_query($conn, $query_transaksi);

    // Ambil id_transaksi yang baru dimasukkan
    $id_transaksi = mysqli_insert_id($conn);

    // Masukkan setiap produk yang ada di keranjang ke tabel detail_transaksi
    foreach ($cart as $item) {
        $id_produk = $item["id_produk"];
        $jumlah = $item["jumlah"];
        $harga = $item["harga"];
        $subtotal = $jumlah * $harga;

        // Masukkan data ke tabel detail_transaksi
        $query_detail = "INSERT INTO detail_transaksi (id_transaksi, id_produk, qty, subtotal) 
                         VALUES ('$id_transaksi', '$id_produk', '$jumlah', '$subtotal')";
        
        mysqli_query($conn, $query_detail);
    }

    // Hapus keranjang setelah checkout
    unset($_SESSION["keranjang"]);

    // Redirect ke halaman histori atau konfirmasi dengan pesan sukses
    echo '<script>alert("Checkout berhasil! Terima kasih atas pembeliannya.");location.href="histori.php";</script>';
} else {
    // Jika keranjang kosong, kembalikan ke halaman keranjang dengan pesan
    echo '<script>alert("Keranjang belanja Anda kosong.");location.href="keranjang.php";</script>';
}
?>
