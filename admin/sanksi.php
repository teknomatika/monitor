<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Data Sanksi <?=tbl_tambah('Input Sanksi','?hal=sanksi&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th>Jenis Sanksi</th>
			<th class="col-lg-2 text-center">Poin</th>
			<th class="col-lg-2 text-center">Status</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('sanksi','*',null,null,'minimal asc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$i++;?></td>
			<td><?=$d['nama'];?></td>
			<td class="text-center"><?=$d['minimal'];?>-<?=$d['maksimal'];?></td>
			<td class="text-center"><?=($d['aktif']==0) ? 'Non-Aktif' : 'Aktif';?></td>
			<td class="text-center"><?=tbl_ubah('?hal=sanksi&act=ubah&id='.$d['id']);?> <?=tbl_hapus('?hal=sanksi&act=hapus&a='.$d['aktif'].'&id='.$d['id']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			if(isset($_GET['id'])){ 
				echo '<h1 class="page-header">Ubah Data Sanksi <small>| <a href="?hal=sanksi">Kembali</a></small></h1>';
				$id = $_GET['id'];
				$db->select('sanksi','*',NULL,"id='$id'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$jum = $db->numRows();
				if($jum<1){ eksyen('Data tidak ditemukan','?hal=sanksi'); }
				$d = $db->getResult();
			}else{
				echo '<h1 class="page-header">Tambah Data Sanksi <small>| <a href="?hal=sanksi">Kembali</a></small></h1>';
			}

			if(isset($_POST['nama'])){
				echo "Processing...";
				$nama = $db->escapeString($_POST['nama']);
				$minimal = $db->escapeString($_POST['minimal']);
				$maksimal = $db->escapeString($_POST['maksimal']);

				if(isset($_POST['id'])){
					$id = mysql_real_escape_string($_POST['id']);
					$db->update('sanksi',array('nama'=>$nama,'minimal'=>$minimal,'maksimal'=>$maksimal,'ubah'=>wkt()),'id="'.$id.'"');
					eksyen('Data berhasil diubah','?hal=sanksi');
				}else{
					$db->insert('sanksi',array('nama'=>$nama,'minimal'=>$minimal,'maksimal'=>$maksimal,'ubah'=>wkt()));
					$res = $db->getResult();
					eksyen('Data berhasil diinput','?hal=sanksi');
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				<?php } ?>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Jenis Sanksi :</label>
					<div class="col-sm-10">
						<input type="text" name="nama" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['nama'];?>"<?php } ?> required="required" maxlength="150">
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Minimal Poin :</label>
					<div class="col-sm-2">
						<input type="text" name="minimal" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['minimal'];?>"<?php } ?> required="required" maxlength="3" <?=angka();?>>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Maksimal Poin :</label>
					<div class="col-sm-2">
						<input type="text" name="maksimal" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['maksimal'];?>"<?php } ?> maxlength="3" <?=angka();?>>
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
			echo '<h1 class="page-header">Hapus Data Sanksi</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);
			if($_GET['a']==1){
				$db->update('sanksi',array('aktif'=>'0'),"id='$id'"); 
			}else{
				$db->update('sanksi',array('aktif'=>'1'),"id='$id'"); 
			}
			$res = $db->getResult();
			eksyen('','?hal=sanksi');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}