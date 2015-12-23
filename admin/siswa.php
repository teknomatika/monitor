<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Master Data Siswa <?=tbl_tambah('Input Data Siswa','?hal=siswa&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th class="col-lg-2 text-center">NIS</th>
			<th>Nama Siswa</th>
			<th class="col-lg-3 text-center">Jurusan</th>
			<th class="col-lg-2 text-center">Kelas (Tapel)</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('siswa','*',null,null,'tapel desc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$i++;?></td>
			<td class="text-center"><?=$d['nis'];?></td>
			<td><?=$d['nama'];?></td>
			<td class="text-center"><?=konvert('jurusan',$d['jurusan'],'nama');?></td>
			<td class="text-center"><?=konvert('kelas',$d['kelas'],'nama');?> (<?=konvert('tapel',$d['tapel'],'awal');?> / <?=konvert('tapel',$d['tapel'],'awal')+1;?>)</td>
			<td class="text-center"><?=tbl_ubah('?hal=siswa&act=ubah&id='.$d['id']);?> <?=tbl_hapus('?hal=siswa&act=hapus&id='.$d['id']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			if(isset($_GET['id'])){ 
				echo '<h1 class="page-header">Ubah Data Siswa <small>| <a href="?hal=siswa">Kembali</a></small></h1>';
				$id = $_GET['id'];
				$db->select('siswa','*',NULL,"id='$id'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$d = $db->getResult();
			}else{
				echo '<h1 class="page-header">Tambah Data Siswa <small>| <a href="?hal=siswa">Kembali</a></small></h1>';
			}

			if(isset($_POST['nama'])){
				echo "Processing...";
				$nama = mysql_real_escape_string($_POST['nama']);
				$_nis = mysql_real_escape_string($_POST['nis']);
				$jk = mysql_real_escape_string($_POST['jk']);
				$tempat = mysql_real_escape_string($_POST['tempat']);
				$tanggal = mysql_real_escape_string($_POST['tanggal']);
				$jurusan = mysql_real_escape_string($_POST['jurusan']);
				$kelas = mysql_real_escape_string($_POST['kelas']);
				$alamat = mysql_real_escape_string($_POST['alamat']);
				$tapel = mysql_real_escape_string($_POST['tapel']);

				// cari NIM
				$nis = getnis($jurusan);
				// end of cari NIM

				// foto siswa
				if($_FILES['foto']['name']!=""){				// kalau tidak kosong, maka dijalankan
					$tmp_name  = $_FILES['foto']['tmp_name']; 	//nama local temp file di server
				    $file_type = $_FILES['foto']['type'];
				    $img_name = $_FILES['foto']['name'];
				    $tipe = array("image/jpeg","image/png","image/gif");
				        if(!in_array($file_type, $tipe)) eksyen('Improper File Type for Photo. Use JPEG/JPG/PNG/GIF only.','?=hal=siswa');

			      	// hapus foto jika sudah ada
			        $db->select('siswa','foto',NULL,"id='$id'",null);
			        $f = $db->getResult();
			        foreach ($f as $f) {
			        	if($f['foto']!=""){
			        		unlink("".$f['foto']);
			        	}
			        }

			        // upload foto ke images/fotosiswa
			        $dir = "images/fotosiswa/".$_nis."-".$img_name;
			        if(move_uploaded_file($tmp_name, $dir)){
			        	echo "<b>Upload Foto sukses!</b>";
			        	$db->update('siswa',array('foto'=>$dir),'id="'.$id.'"');
			        }else{
			        	echo "<b>Upload Foto gagal!</b>";
			        }
				}

				if(isset($_POST['id'])){
					$id = mysql_real_escape_string($_POST['id']);
					$q = $db->update('siswa',array('nama'=>$nama,'jk'=>$jk,'alamat'=>$alamat,'tempat'=>$tempat,'tanggal'=>$tanggal,'jurusan'=>$jurusan,'kelas'=>$kelas,'tapel'=>$tapel,'ubah'=>wkt()),'id="'.$id.'"');
					if($q){
						eksyen('Data berhasil diubah','?hal=siswa');
					}else{
						eksyen('Data gagal diubah','?hal=siswa');
					}
				}else{
					$q = $db->insert('siswa',array('nama'=>$nama,'nis'=>$nis,'jk'=>$jk,'alamat'=>$alamat,'tempat'=>$tempat,'tanggal'=>$tanggal,'jurusan'=>$jurusan,'kelas'=>$kelas,'tapel'=>$tapel,'buat'=>wkt()));
					if($q){
						$db->insert('poin',array('nis'=>$nis,'poin'=>'0','ubah'=>wkt()));
						eksyen('Data berhasil diinput','?hal=siswa');
					}else{
						eksyen('Data gagal diinput','?hal=siswa');
					}
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
			  <div class="col-lg-8">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				
				<div class="form-group">
					<label for="inputNIS" class="col-sm-2 control-label">NIS :</label>
					<div class="col-sm-10">
						<input type="text" name="nis" id="inputNIS" class="form-control" value="<?=$d[0]['nis'];?>" <?=angka();?> readonly>
					</div>
				</div>
				<?php } ?>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Nama Siswa :</label>
					<div class="col-sm-10">
						<input type="text" name="nama" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['nama'];?>"<?php } ?> required="required" maxlength="25">
					</div>
				</div>

				<div class="form-group">
					<label for="tempat" class="col-sm-2 control-label">Tempat, Tanggal Lahir :</label>
					<div class="col-sm-5">
						<input type="text" name="tempat" id="tempat" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['tempat'];?>"<?php } ?> maxlength="25" required="required">
					</div>
					<div class="col-sm-5">
						<input type="text" name="tanggal" id="datepicker" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['tanggal'];?>"<?php } ?> required="required">
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Jenis Kelamin:</label>
					<div class="col-sm-10">
						<div class="radio">
							<label>
								<input type="radio" name="jk" id="inputJk" value="L" <?php if(isset($_GET['id'])){ cekbok($d[0]['jk'],'L'); }else{ ?>checked="checked"<?php } ?>>
								Laki-laki
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="jk" id="inputJk" value="P" <?php if(isset($_GET['id'])){ cekbok($d[0]['jk'],'P'); }?>>
								Perempuan
							</label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="inputTunjangan" class="col-sm-2 control-label">Alamat :</label>
					<div class="col-sm-10">
						<textarea name="alamat" id="inputAlamat" class="form-control" rows="3" required="required"><?php if(isset($_GET['id'])){ ?><?=$d[0]['tempat'];?><?php } ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="inputJurusan" class="col-sm-2 control-label">Jurusan:</label>
					<div class="col-sm-10">
						<select name="jurusan" id="inputJurusan" class="form-control" required="required">
							<?php
							$db->select('jurusan'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
							$jb = $db->getResult();
							foreach($jb as $jb){
							?>
							<option value="<?=$jb['id'];?>" <?php if(isset($_GET['id'])){ selek($d[0]['jurusan'],$jb['id']); }else{ ?>selected<?php } ?>><?=$jb['nama'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="inputKelas" class="col-sm-2 control-label">Kelas:</label>
					<div class="col-sm-5">
						<select name="kelas" id="inputKelas" class="form-control" required="required">
							<?php
							$db->select('kelas','*',null,"aktif='1'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
							$jb = $db->getResult();
							foreach($jb as $jb){
							?>
							<option value="<?=$jb['id'];?>" <?php if(isset($_GET['id'])){ selek($d[0]['kelas'],$jb['id']); }else{ ?>selected<?php } ?>><?=$jb['nama'];?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-5">
						<select name="tapel" id="inputKelas" class="form-control" required="required">
							<?php
							$db->select('tapel','*',null,"aktif='1'",'awal asc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
							$jb = $db->getResult();
							foreach($jb as $jb){
							?>
							<option value="<?=$jb['id'];?>" <?php if(isset($_GET['id'])){ selek($d[0]['tapel'],$jb['id']); }else{ ?>selected<?php } ?>><?=$jb['awal'];?> / <?=$jb['akhir'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="inputFoto" class="col-sm-2 control-label">Foto Siswa :</label>
					<div class="col-sm-10">
						<input type="file" name="foto" id="inputFoto">
					</div>
				</div>
			
				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-2">
						<button type="submit" class="btn btn-primary">Simpan</button>
						<button type="reset" class="btn btn-default">Reset</button>
					</div>
				</div>
			  </div>
			  <div class="col-lg-4">
			   <?php if(isset($_GET['id'])){ ?>
			  	<?php if($d[0]['foto'] != ""){ ?>
			  	<img src="<?=$d[0]['foto'];?>" width="50%">
			  	<?php } ?>
			   <?php } ?>
			  </div>
			</form>
			<?php break;

		case 'hapus':
			echo '<h1 class="page-header">Hapus Data Siswa</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);

			// hapus foto
			$db->select('siswa','foto',NULL,"id='$id'",null);
	        $f = $db->getResult();
	        foreach ($f as $f) {
	        	if($f['foto']!=""){
	        		unlink("".$f['foto']);
	        	}
	        }

	        // hapus data
			$db->delete('siswa',"id='$id'"); 
			$res = $db->getResult();
			eksyen('','?hal=siswa');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}