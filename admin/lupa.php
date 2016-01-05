<div class="col-lg-12">
    <h1 class="page-header">Lupa Password?</h1>
</div>

<?php
if(isset($_POST['username'])){
	$username = $db->escapeString($_POST['username']);
	$nama = $db->escapeString($_POST['nama']);
	$jurusan = $db->escapeString($_POST['jurusan']);

	$db->select('users','*',NULL,"username='$username' and nama='$nama' and jurusan='$jurusan'",null);
	$data = $db->getResult();
	if($data){
		$str = 'abcdef1234567890';
		$shuffled = str_shuffle($str);
		$md5pass = md5($shuffled);
		$db->update('users',array('password'=>$md5pass),"username='$username' and nama='$nama' and jurusan='$jurusan'");
		echo 'Selamat, Anda berhasil mengubah password Anda. Password baru Anda adalah '.$shuffled;
	}else{
		eksyen('Maaf, data tidak ditemukan','index.php');
	}
}
?>

<form action="" method="POST" class="form-horizontal" role="form">
		<div class="form-group">
			<label for="inputUsername" class="col-sm-2 control-label">Username:</label>
			<div class="col-sm-10">
				<input type="text" name="username" id="inputUsername" class="form-control" value="" required="required">
			</div>
		</div>

		<div class="form-group">
			<label for="inputNama" class="col-sm-2 control-label">Nama:</label>
			<div class="col-sm-10">
				<input type="text" name="nama" id="inputNama" class="form-control" value="" required="required">
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
					<option value="<?=$jb['id'];?>"><?=$jb['nama'];?></option>
					<?php } ?>
				</select>
				<p class="help-block">Isi sesuai dengan data diri Anda. Huruf bersifat <em>case-sensitive</em>, artinya "Aku" dan "aku" adalah 2 hal yang berbeda.</p>
			</div>
		</div>		

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary">Proses</button>
			</div>
		</div>
</form>