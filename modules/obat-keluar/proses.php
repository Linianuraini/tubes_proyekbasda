<?php
session_start();

// Panggil koneksi database.php untuk koneksi database
require_once "../../config/database.php";

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman login dan tampilkan pesan = 1
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=1'>";
}
// jika user sudah login, maka jalankan perintah untuk insert, update, dan delete
else {
    if ($_GET['act']=='insert') {
        if (isset($_POST['simpan'])) {
            // ambil data hasil submit dari form
            $kode_transaksi_keluar = mysqli_real_escape_string($mysqli, trim($_POST['kode_transaksi_keluar']));
            
            $tanggal         = mysqli_real_escape_string($mysqli, trim($_POST['tanggal_Keluar']));
            $exp             = explode('-',$tanggal);
            $tanggal_Keluar  = $exp[2]."-".$exp[1]."-".$exp[0];
            
            $kode_obat       = mysqli_real_escape_string($mysqli, trim($_POST['kode_obat']));
            $jumlah_Keluar    = mysqli_real_escape_string($mysqli, trim($_POST['jumlah_Keluar']));
            $total_stok      = mysqli_real_escape_string($mysqli, trim($_POST['total_stok']));
            
            $created_user    = $_SESSION['id_user'];

            // perintah query untuk menyimpan data ke tabel obat Keluar
            $query = mysqli_query($mysqli, "INSERT INTO is_obat_Keluar(kode_transaksi_keluar,tanggal_Keluar,kode_obat,jumlah_Keluar,created_user) 
                                            VALUES('$kode_transaksi_keluar','$tanggal_Keluar','$kode_obat','$jumlah_Keluar','$created_user')")
                                            or die('Ada kesalahan pada query insert : '.mysqli_error($mysqli));    

            // cek query
            if ($query) {
                // perintah query untuk mengubah data pada tabel obat
                $query1 = mysqli_query($mysqli, "UPDATE is_obat SET stok        = '$total_stok'
                                                              WHERE kode_obat   = '$kode_obat'")
                                                or die('Ada kesalahan pada query update : '.mysqli_error($mysqli));

                // cek query
                if ($query1) {                       
                    // jika berhasil tampilkan pesan berhasil simpan data
                    header("location: ../../main.php?module=obat_Keluar&alert=1");
                }
            }   
        }   
    }
}       
?>