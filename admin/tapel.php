<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Data Tahun Pelajaran <?=tbl_tambah('Input Tapel','?hal=tapel&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th>Tahun Pelajaran</th>
			<th class="col-lg-2 text-center">Status</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('tapel'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$i++;?></td>
			<td><?=$d['tapel_awal'];?> / <?=$d['tapel_akhir'];?></td>
			<td class="text-center"><?=($d['aktif']==1) ? "Aktif" : "Non-Aktif";?></td>
			<td class="text-center"><?=tbl_ubah('?hal=tapel&act=ubah&id='.$d['id']);?> <?=tbl_hapus('?hal=tapel&act=hapus&a='.$d['aktif'].'&id='.$d['id']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			if(isset($_GET['id'])){ 
				echo '<h1 class="page-header">Ubah Data Tahun Pelajaran <small>| <a href="?hal=tapel">Kembali</a></small></h1>';
				$id = $_GET['id'];
				$db->select('tapel','*',NULL,"id='$id'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$jum = $db->numRows();
				if($jum<1){ eksyen('Data tidak ditemukan','?hal=tapel'); }
				$d = $db->getResult();
			}else{
				echo '<h1 class="page-header">Tambah Data Tahun Pelajaran <small>| <a href="?hal=tapel">Kembali</a></small></h1>';
			}

			if(isset($_POST['awal'])){
				echo "Processing...";
				$awal = $db->escapeString($_POST['awal']);
				$akhir = $awal + 1;

				if(isset($_POST['id'])){
					$id = mysql_real_escape_string($_POST['id']);
					$db->update('tapel',array('tapel_awal'=>$awal,'tapel_akhir'=>$akhir),'id="'.$id.'"');
					eksyen('Data berhasil diubah','?hal=tapel');
				}else{
					$db->insert('tapel',array('tapel_awal'=>$awal,'tapel_akhir'=>$akhir));
					$res = $db->getResult();
					eksyen('Data berhasil diinput','?hal=tapel');
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				<?php } ?>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Tahun Pelajaran :</label>
					<div class="col-sm-3">
						<input type="text" name="awal" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['tapel_awal'];?>"<?php } ?> required="required" maxlength="4" <?=angka();?>>
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
			echo '<h1 class="page-header">Hapus Data Tahun Pelajaran</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);
			if($_GET['a']==1){
				$db->update('tapel',array('aktif'=>'0'),"id='$id'"); 
			}else{
				$db->update('tapel',array('aktif'=>'1'),"id='$id'"); 
			}
			$res = $db->getResult();
			eksyen('','?hal=tapel');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}