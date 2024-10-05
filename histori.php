<?php

include "koneksi.php";
include "header_pelanggan.php"; // Menyertakan header pelanggan

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo '<script>alert("Anda harus login untuk melihat histori pembelian."); location.href="login.php";</script>';
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan']; // Ambil id pelanggan dari session

// Ambil semua pemesanan yang dilakukan oleh pelanggan
$qry_pemesanan = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id_pelanggan = '$id_pelanggan' ORDER BY tanggal_pemesanan DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Histori Pembelian</h2>

        <?php if (mysqli_num_rows($qry_pemesanan) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal Pemesanan</th>
                        <th>Total Belanja</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data_pemesanan = mysqli_fetch_array($qry_pemesanan)): ?>
                        <tr>
                            <td><?= $data_pemesanan['tanggal_pemesanan'] ?></td>
                            <td>Rp <?= number_format($data_pemesanan['total_belanja'], 0, ',', '.') ?></td>
                            <td>
                                <a href="detail_pemesanan.php?id_pemesanan=<?= $data_pemesanan['id_pemesanan'] ?>" class="btn btn-info">Lihat Detail</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada histori pembelian.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
