<script type="text/javascript">
  function tampil_obat(input){
    var num = input.value;

    $.post("modules/obat-Keluar/obat.php", {
      dataidobat: num,
    }, function(response) {      
      $('#stok').html(response)

      document.getElementById('jumlah_Keluar').focus();
    });
  }

  function cek_jumlah_Keluar(input) {
    jml = document.formObatKeluar.jumlah_.value;
    var jumlah = eval(jml);
    if(jumlah < 1){
      alert('Jumlah Keluar Tidak Boleh Nol !!');
      input.value = input.value.substring(0,input.value.length-1);
    }
  }

  function hitung_total_stok() {
    bil1 = document.formObatKeluar.stok.value;
    bil2 = document.formObatKeluar.jumlah_Keluar.value;

    if (bil2 == "") {
      var hasil = "";
    }
    else {
      var hasil = eval(bil1) - eval(bil2);
    }

    document.formObatKeluar.total_stok.value = (hasil);
  }
</script>

<?php  
// fungsi untuk pengecekan tampilan form
// jika form add data yang dipilih
if ($_GET['form']=='add') { ?> 
  <!-- tampilan form add data -->
	<!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-edit icon-title"></i> Input Data Obat Keluar
    </h1>
    <ol class="breadcrumb">
      <li><a href="?module=beranda"><i class="fa fa-home"></i> Beranda </a></li>
      <li><a href="?module=obat_Keluar"> Obat Keluar </a></li>
      <li class="active"> Tambah </li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <!-- form start -->
          <form role="form" class="form-horizontal" action="modules/obat-Keluar/proses.php?act=insert" method="POST" name="formObatKeluar">
            <div class="box-body">
              <?php  
              // fungsi untuk membuat kode transaksi
              $query_id1 = mysqli_query($mysqli, "SELECT RIGHT(kode_transaksi_keluar,7) as kode FROM is_obat_Keluar
                                                ORDER BY kode_transaksi_keluar DESC LIMIT 1")
                                                or die('Ada kesalahan pada query tampil kode_transaksi_keluar : '.mysqli_error($mysqli));

              $count = mysqli_num_rows($query_id1);

              if ($count <> 0) {
                  // mengambil data kode transaksi
                  $data_id1 = mysqli_fetch_assoc($query_id1);
                  $kode1   = $data_id1['kode']+1;
              } else {
                  $kode1 = 1;
              }

              // buat kode_transaksi
              $tahun          = date("Y");
              $buat_id1        = str_pad($kode1, 7, "0", STR_PAD_LEFT);
              $kode_transaksi_keluar = "TK-$tahun-$buat_id1";
              ?>

              <div class="form-group">
                <label class="col-sm-2 control-label">Kode Transaksi Keluar</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="kode_transaksi_keluar" value="<?php echo $kode_transaksi_keluar; ?>" readonly required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Tanggal</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="tanggal_Keluar" autocomplete="off" value="<?php echo date("d-m-Y"); ?>" required>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <label class="col-sm-2 control-label">Obat</label>
                <div class="col-sm-5">
                  <select class="chosen-select" name="kode_obat" data-placeholder="-- Pilih Obat --" onchange="tampil_obat(this)" autocomplete="off" required>
                    <option value=""></option>
                    <?php
                      $query_obat = mysqli_query($mysqli, "SELECT kode_obat, nama_obat FROM is_obat ORDER BY nama_obat ASC")
                                                            or die('Ada kesalahan pada query tampil obat: '.mysqli_error($mysqli));
                      while ($data_obat = mysqli_fetch_assoc($query_obat)) {
                        echo"<option value=\"$data_obat[kode_obat]\"> $data_obat[kode_obat] | $data_obat[nama_obat] </option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              
              <span id='stok'>
              <div class="form-group">
                <label class="col-sm-2 control-label">Stok</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="stok" name="stok" readonly required>
                </div>
              </div>
              </span>

              <div class="form-group">
                <label class="col-sm-2 control-label">Jumlah Keluar</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="jumlah_Keluar" name="jumlah_Keluar" autocomplete="off" onKeyPress="return goodchars(event,'0123456789',this)" onkeyup="hitung_total_stok(this)&cek_jumlah_Keluar(this)" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Total Stok</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="total_stok" name="total_stok" readonly required>
                </div>
              </div>

            </div><!-- /.box body -->

            <div class="box-footer">
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" class="btn btn-primary btn-submit" name="simpan" value="Simpan">
                  <a href="?module=obat_Keluar" class="btn btn-default btn-reset">Batal</a>
                </div>
              </div>
            </div><!-- /.box footer -->
          </form>
        </div><!-- /.box -->
      </div><!--/.col -->
    </div>   <!-- /.row -->
  </section><!-- /.content -->
<?php
}
?>