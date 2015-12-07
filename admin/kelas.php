<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Data Kelas <?=tbl_tambah('Input Kelas','?hal=kelas&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th>Nama Kelas</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('kelas'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$i++;?></td>
			<td><?=$d['nama'];?></td>
			<td class="text-center"><?=tbl_ubah('?hal=kelas&act=ubah&id='.$d['id']);?> <?=tbl_hapus('?hal=kelas&act=hapus&id='.$d['id']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			if(isset($_GET['id'])){ 
				echo '<h1 class="page-header">Ubah Data Kelas <small>| <a href="?hal=kelas">Kembali</a></small></h1>';
				$id = $_GET['id'];
				$db->select('kelas','*',NULL,"id='$id'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$jum = $db->numRows();
				if($jum<1){ eksyen('Data tidak ditemukan','?hal=kelas'); }
				$d = $db->getResult();
			}else{
				echo '<h1 class="page-header">Tambah Data Kelas <small>| <a href="?hal=kelas">Kembali</a></small></h1>';
			}

			if(isset($_POST['nama'])){
				echo "Processing...";
				$nama = $db->escapeString($_POST['nama']);

				if(isset($_POST['id'])){
					$id = mysql_real_escape_string($_POST['id']);
					$db->update('kelas',array('nama'=>$nama,'ubah'=>wkt()),'id="'.$id.'"');
					eksyen('Data berhasil diubah','?hal=kelas');
				}else{
					$db->insert('kelas',array('nama'=>$nama,'buat'=>wkt()));
					$res = $db->getResult();
					eksyen('Data berhasil diinput','?hal=kelas');
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				<?php } ?>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Nama Kelas :</label>
					<div class="col-sm-3">
						<input type="text" name="nama" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['nama'];?>"<?php } ?> required="required" maxlength="30">
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
			echo '<h1 class="page-header">Hapus Data Jurusan</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);
			$db->delete('kelas',"id='$id'"); 
			$res = $db->getResult();
			eksyen('','?hal=kelas');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}