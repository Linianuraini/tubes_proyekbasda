<?php
session_start();
ob_start();

// Panggil koneksi database.php untuk koneksi database
require_once "../../config/database.php";
// panggil fungsi untuk format tanggal
include "../../config/fungsi_tanggal.php";
// panggil fungsi untuk format rupiah
include "../../config/fungsi_rupiah.php";

$hari_ini = date("d-m-Y");

// ambil data hasil submit dari form
$tgl1     = $_GET['tgl_awal'];
$explode  = explode('-',$tgl1);
$tgl_awal = $explode[2]."-".$explode[1]."-".$explode[0];

$tgl2      = $_GET['tgl_akhir'];
$explode   = explode('-',$tgl2);
$tgl_akhir = $explode[2]."-".$explode[1]."-".$explode[0];

if (isset($_GET['tgl_awal'])) {
    $no    = 1;
    // fungsi query untuk menampilkan data dari tabel obat Keluar
    $query = mysqli_query($mysqli, "SELECT a.kode_transaksi_keluar,a.tanggal_Keluar,a.kode_obat,a.jumlah_Keluar,b.kode_obat,b.nama_obat,b.satuan
                                    FROM is_obat_Keluar as a INNER JOIN is_obat as b ON a.kode_obat=b.kode_obat
                                    WHERE a.tanggal_Keluar BETWEEN '$tgl_awal' AND '$tgl_akhir'
                                    ORDER BY a.kode_transaksi_keluar ASC") 
                                    or die('Ada kesalahan pada query tampil Transaksi : '.mysqli_error($mysqli));
    $count  = mysqli_num_rows($query);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>LAPORAN DATA OBAT Keluar</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/laporan.css" />
</head>
<body>
    <div id="title">
        LAPORAN DATA OBAT Keluar
    </div>
    <?php  
    if ($tgl_awal == $tgl_akhir) { ?>
        <div id="title-tanggal">
            Tanggal <?php echo tgl_eng_to_ind($tgl1); ?>
        </div>
    <?php
    } else { ?>
        <div id="title-tanggal">
            Tanggal <?php echo tgl_eng_to_ind($tgl1); ?> s.d. <?php echo tgl_eng_to_ind($tgl2); ?>
        </div>
    <?php
    }
    ?>

    <hr><br>
    <div id="isi">
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0">
            <thead style="background:#e8ecee">
                <tr class="tr-title">
                    <th height="20" align="center" valign="middle">NO.</th>
                    <th height="20" align="center" valign="middle">KODE TRANSAKSI Keluar</th>
                    <th height="20" align="center" valign="middle">TANGGAL</th>
                    <th height="20" align="center" valign="middle">KODE OBAT</th>
                    <th height="20" align="center" valign="middle">NAMA OBAT</th>
                    <th height="20" align="center" valign="middle">JUMLAH Keluar</th>
                    <th height="20" align="center" valign="middle">SATUAN</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // jika data ada
            if($count == 0) {
                echo "  <tr>
                            <td width='40' height='13' align='center' valign='middle'></td>
                            <td width='120' height='13' align='center' valign='middle'></td>
                            <td width='80' height='13' align='center' valign='middle'></td>
                            <td width='80' height='13' align='center' valign='middle'></td>
                            <td style='padding-left:5px;' width='155' height='13' valign='middle'></td>
                            <td style='padding-right:10px;' width='100' height='13' align='right' valign='middle'></td>
                            <td width='80' height='13' align='center' valign='middle'></td>
                        </tr>";
            }
            // jika data tidak ada
            else {
                // tampilkan data
                while ($data = mysqli_fetch_assoc($query)) {
                    $tanggal       = $data['tanggal_Keluar'];
                    $exp           = explode('-',$tanggal);
                    $tanggal_Keluar = $exp[2]."-".$exp[1]."-".$exp[0];

                    // menampilkan isi tabel dari database ke tabel di aplikasi
                    echo "  <tr>
                                <td width='40' height='13' align='center' valign='middle'>$no</td>
                                <td width='120' height='13' align='center' valign='middle'>$data[kode_transaksi_keluar]</td>
                                <td width='80' height='13' align='center' valign='middle'>$tanggal_Keluar</td>
                                <td width='80' height='13' align='center' valign='middle'>$data[kode_obat]</td>
                                <td style='padding-left:5px;' width='155' height='13' valign='middle'>$data[nama_obat]</td>
                                <td style='padding-right:10px;' width='100' height='13' align='right' valign='middle'>$data[jumlah_Keluar]</td>
                                <td width='80' height='13' align='center' valign='middle'>$data[satuan]</td>
                            </tr>";
                    $no++;
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>