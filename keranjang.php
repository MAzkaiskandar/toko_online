<?php

// Koneksi ke database
include 'koneksi.php';
include 'header_pelanggan.php'; // Tambahkan header

// Periksa apakah ada produk yang dipilih untuk ditambahkan ke keranjang
if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];
    $jumlah = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1; // Ambil jumlah produk

    // Ambil data produk dari database berdasarkan id_produk
    $qry_produk = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
    $data_produk = mysqli_fetch_array($qry_produk);

    // Cek apakah session keranjang sudah ada, jika belum buat keranjang baru
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Cek apakah produk sudah ada di keranjang
    if (isset($_SESSION['keranjang'][$id_produk])) {
        // Jika produk sudah ada, tambahkan jumlah produk sesuai input
        $_SESSION['keranjang'][$id_produk]['jumlah'] += $jumlah;
    } else {
        // Jika produk belum ada di keranjang, tambahkan produk baru
        $_SESSION['keranjang'][$id_produk] = [
            'id_produk' => $data_produk['id_produk'],
            'nama_produk' => $data_produk['nama_produk'],
            'harga' => $data_produk['harga'],
            'jumlah' => $jumlah
        ];
    }
}

// Tampilkan isi keranjang
?>
<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Keranjang Belanja</h2>

        <!-- Tampilkan keranjang belanja -->
        <?php
        if (!empty($_SESSION['keranjang'])) {
            echo "<table class='table table-bordered'>";
            echo "<thead><tr><th>Nama Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead>";
            echo "<tbody>";

            $total = 0;
            foreach ($_SESSION['keranjang'] as $item) {
                $subtotal = $item['harga'] * $item['jumlah'];
                $total += $subtotal;

                echo "<tr>";
                echo "<td>{$item['nama_produk']}</td>";
                echo "<td>Rp " . number_format($item['harga'], 0, ',', '.') . "</td>";
                echo "<td>
                    <form method='POST' action='update_keranjang.php'>
                        <input type='hidden' name='id_produk' value='{$item['id_produk']}'>
                        <input type='number' name='jumlah' value='{$item['jumlah']}' min='1' class='form-control'>
                        <button type='submit' class='btn btn-primary mt-2'>Update</button>
                    </form>
                </td>";
                echo "<td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>";
                echo "<td><a href='hapus_keranjang.php?id_produk={$item['id_produk']}' class='btn btn-danger'>Hapus</a></td>";
                echo "</tr>";
            }

            echo "<tr><td colspan='3'><strong>Total</strong></td><td><strong>Rp " . number_format($total, 0, ',', '.') . "</strong></td></tr>";
            echo "</tbody>";
            echo "</table>";

            // Tombol Checkout
            echo "<div class='text-end'>";
            echo "<a href='checkout.php' class='btn btn-success'>Checkout</a>";
            echo "</div>";
        } else {
            echo "<p>Keranjang belanja Anda kosong.</p>";
        }
        ?>
    </div>
</body>
</html>
