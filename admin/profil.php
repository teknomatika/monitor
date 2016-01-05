<?php if(isset($_SESSION['username'])){
	$_username = $_SESSION['username'];
	$db->select('users','*',NULL,"username='$_username'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
	$d = $db->getResult();
	if(isset($_POST['nama'])){
		echo "Processing...";
		$nama = mysql_real_escape_string($_POST['nama']);
		$username = mysql_real_escape_string($_POST['username']);
		$level = mysql_real_escape_string($_POST['level']);

		// password
		if($_POST['password']!=""){				// kalau tidak kosong, maka dijalankan
			$password = md5(mysql_real_escape_string($_POST['password']));
			$q = $db->update('users',array('password'=>$password),'username="'.$_username.'"');
		}

		// users
		$q = $db->update('users',array('nama'=>$nama,'level'=>$level,'ubah'=>wkt()),'username="'.$_username.'"');
		if($q){
			eksyen('Data berhasil diubah','?hal=profil');
		}else{
			eksyen('Data gagal diubah','?hal=profil');
		}
	}
?>
<div class="col-lg-12">
    <h1 class="page-header">Ubah Profil</h1>
</div>
<form action="" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">

	<div class="form-group">
		<label for="inputNama" class="col-sm-2 control-label">Nama Guru :</label>
		<div class="col-sm-10">
			<input type="text" name="nama" id="inputNama" class="form-control" value="<?=$d[0]['nama'];?>" required="required" maxlength="50">
		</div>
	</div>

	<div class="form-group">
		<label for="inputFoto" class="col-sm-2 control-label">Username :</label>
		<div class="col-sm-10">
			<input type="text" name="username" id="inputUsername" class="form-control" value="<?=$d[0]['username'];?>" readonly required="required" maxlength="15">
		</div>
	</div>


	<div class="form-group">
		<label for="inputFoto" class="col-sm-2 control-label">Password :</label>
		<div class="col-sm-10">
			<input type="text" name="password" id="inputpassword" class="form-control" maxlength="15">
			<span class="help-block">Kosongkan jika tidak ingin mengubah password.</span>
		</div>
	</div>

	<div class="form-group">
		<label for="inputNama" class="col-sm-2 control-label">Level :</label>
		<div class="col-sm-10">
			<div class="radio">
				<label>
					<input type="radio" name="level" id="level" value="Admin" <?php cekbok($d[0]['level'],'Admin');?>>
					Admin
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="level" id="level" value="Kepsek" <?php cekbok($d[0]['level'],'Kepsek');?>>
					Kepala Sekolah
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="reset" class="btn btn-default">Reset</button>
		</div>
	</div>
</form>

<?php 
}else{
	die("<script>window.location.assign('index.php');</script>");
}
?>