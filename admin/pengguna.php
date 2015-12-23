<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Master Data Pengguna <?=tbl_tambah('Input Data Pengguna','?hal=pengguna&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th class="col-lg-2 text-center">Username</th>
			<th colspan="2">Nama Guru</th>
			<th class="col-lg-2 text-center">Level</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('users','*',null,null,'id asc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$i++;?></td>
			<td class="text-center"><?=$d['username'];?></td>
			<td><?=$d['nama'];?></td>
			<td class="col-lg-2 text-center"><?=($d['jk']=="L") ? "Laki-laki" : "Perempuan";?></td>
			<td class="text-center"><?=$d['level'];?></td>
			<td class="text-center"><?=tbl_ubah('?hal=pengguna&act=ubah&id='.$d['id']);?> <?=tbl_hapus('?hal=pengguna&act=hapus&id='.$d['id']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			if(isset($_GET['id'])){ 
				echo '<h1 class="page-header">Ubah Data Pengguna <small>| <a href="?hal=pengguna">Kembali</a></small></h1>';
				$id = $_GET['id'];
				$db->select('users','*',NULL,"id='$id'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$d = $db->getResult();
			}else{
				echo '<h1 class="page-header">Tambah Data Pengguna <small>| <a href="?hal=pengguna">Kembali</a></small></h1>';
			}

			if(isset($_POST['nama'])){
				echo "Processing...";
				$nama = mysql_real_escape_string($_POST['nama']);
				$jk = mysql_real_escape_string($_POST['jk']);
				$alamat = mysql_real_escape_string($_POST['alamat']);
				$username = mysql_real_escape_string($_POST['username']);
				$level = mysql_real_escape_string($_POST['level']);

				if(isset($_POST['id'])){
					$id = mysql_real_escape_string($_POST['id']);

					// password
					if($_POST['password']!=""){				// kalau tidak kosong, maka dijalankan
						$password = md5(mysql_real_escape_string($_POST['password']));
						$q = $db->update('users',array('password'=>$password),'id="'.$id.'"');
					}

					// users
					$q = $db->update('users',array('nama'=>$nama,'jk'=>$jk,'alamat'=>$alamat,'level'=>$level,'ubah'=>wkt()),'id="'.$id.'"');
					if($q){
						eksyen('Data berhasil diubah','?hal=pengguna');
					}else{
						eksyen('Data gagal diubah','?hal=pengguna');
					}
				}else{
					$password = md5(mysql_real_escape_string($_POST['password']));
					$q = $db->insert('users',array('nama'=>$nama,'jk'=>$jk,'alamat'=>$alamat,'level'=>$level,'username'=>$username,'password'=>$password,'buat'=>wkt()));
					if($q){
						eksyen('Data berhasil diinput','?hal=pengguna');
					}else{
						eksyen('Data gagal diinput','?hal=pengguna');
					}
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				<?php } ?>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Nama Guru :</label>
					<div class="col-sm-10">
						<input type="text" name="nama" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['nama'];?>"<?php } ?> required="required" maxlength="50">
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
						<textarea name="alamat" id="inputAlamat" class="form-control" rows="3" required="required"><?php if(isset($_GET['id'])){ ?><?=$d[0]['alamat'];?><?php } ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="inputFoto" class="col-sm-2 control-label">Username :</label>
					<div class="col-sm-10">
						<input type="text" name="username" id="inputUsername" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['username'];?>" readonly<?php } ?> required="required" maxlength="15">
					</div>
				</div>


				<div class="form-group">
					<label for="inputFoto" class="col-sm-2 control-label">Password :</label>
					<div class="col-sm-10">
						<input type="text" name="password" id="inputpassword" class="form-control" maxlength="15" <?php if(!isset($_GET['id'])){ ?>required<?php } ?>>
						<span class="help-block">Kosongkan jika tidak ingin mengubah password.</span>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Level :</label>
					<div class="col-sm-10">
						<div class="radio">
							<label>
								<input type="radio" name="level" id="level" value="Admin" <?php if(isset($_GET['id'])){ cekbok($d[0]['level'],'Admin'); }else{ ?>checked="checked"<?php } ?>>
								Admin
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="level" id="level" value="Kepsek" <?php if(isset($_GET['id'])){ cekbok($d[0]['level'],'Kepsek'); }?>>
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
			<?php break;

		case 'hapus':
			echo '<h1 class="page-header">Hapus Data Pengguna</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);
			$db->delete('users',"id='$id'"); 
			$res = $db->getResult();
			eksyen('','?hal=pengguna');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}