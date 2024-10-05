<?php
if ($_POST) {
    include "koneksi.php";

    $id_pelanggan = $_POST['id_pelanggan'];
    $id_petugas = $_POST['id_petugas'];
    $tgl_transaksi = $_POST['tgl_transaksi'];

    // Periksa apakah produk[] dan qty[] terdefinisi sebelum digunakan
    if (isset($_POST['produk']) && isset($_POST['qty'])) {
        $produk = $_POST['produk'];
        $qty = $_POST['qty'];
        

        // Pastikan produk dan qty adalah array dan memiliki elemen
        if (is_array($produk) && is_array($qty) && count($produk) > 0 && count($qty) > 0) {
            
            // Masukkan data transaksi ke dalam tabel transaksi
            $insert_transaksi = mysqli_query($conn, "INSERT INTO transaksi (id_pelanggan, id_petugas, tgl_transaksi) VALUES ('".$id_pelanggan."', '".$id_petugas."', '".$tgl_transaksi."')");

            if ($insert_transaksi) {
                // Ambil ID transaksi yang baru saja dimasukkan
                $id_transaksi = mysqli_insert_id($conn);

                // Loop untuk memasukkan detail transaksi
                for ($i = 0; $i < count($produk); $i++) {
                    if ($qty[$i] > 0) {
                    $id_produk = $produk[$i];
                    $query = mysqli_query($conn,"SELECT * FROM produk WHERE id_produk=$id_produk");
                    $produk = mysqli_fetch_array($query);
                    $subtotal = $produk['harga']*$qty[$i];
                        $insert_detail = mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, qty,subtotal) VALUES ('".$id_transaksi."', '".$produk[$i]."', '".$qty[$i]."','".$subtotal."')");
                    }
                }
                echo "<script>alert('Transaksi berhasil ditambahkan');location.href='tampil_transaksi.php';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan transaksi');location.href='tambah_transaksi.php';</script>";
            }
        } else {
            echo "<script>alert('Produk dan jumlah harus diisi dengan benar');location.href='tambah_transaksi.php';</script>";
        }
    } else {
        echo "<script>alert('Produk dan jumlah tidak boleh kosong');location.href='tambah_transaksi.php';</script>";
    }
}
?>