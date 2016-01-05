<?php if(!isset($_GET['act'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Data Tata Tertib <?=tbl_tambah('Input Tata Tertib','?hal=tatatertib&act=ubah');?></h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">Poin</th>
			<th class="col-lg-1 text-center">Kode</th>
			<th>Nama Tata Tertib</th>
			<th class="col-lg-2 text-center">Jenis</th>
			<th class="col-lg-2 text-center">Status</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('tata_tertib','*',null,null,"poin asc"); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr>
			<td class="text-center"><?=$d['poin'];?></td>
			<td class="text-center">TR-0<?=$d['id'];?></td>
			<td><?=$d['nama'];?></td>
			<td class="text-center"><?=$d['jenis'];?></td>
			<td class="text-center"><?=($d['aktif']==1) ? "Aktif" : "Non-Aktif";?></td>
			<td class="text-center"><?=tbl_ubah('?hal=tatatertib&act=ubah&id='.$d['id']);?> <?=tbl_hapus('?hal=tatatertib&act=hapus&a='.$d['aktif'].'&id='.$d['id']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{

	switch ($_GET['act']) {
		case 'ubah': ?>
			<?php 
			if(isset($_GET['id'])){ 
				echo '<h1 class="page-header">Ubah Data Tata Tertib <small>| <a href="?hal=tatatertib">Kembali</a></small></h1>';
				$id = $_GET['id'];
				$db->select('tata_tertib','*',NULL,"id='$id'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$jum = $db->numRows();
				if($jum<1){ eksyen('Data tidak ditemukan','?hal=tatatertib'); }
				$d = $db->getResult();
			}else{
				echo '<h1 class="page-header">Tambah Data Tata Tertib <small>| <a href="?hal=tatatertib">Kembali</a></small></h1>';
			}

			if(isset($_POST['nama'])){
				echo "Processing...";
				$nama = $db->escapeString($_POST['nama']);
				$poin = $db->escapeString($_POST['poin']);
				$jenis = $db->escapeString($_POST['jenis']);

				if(isset($_POST['id'])){
					$id = mysql_real_escape_string($_POST['id']);
					$db->update('tata_tertib',array('nama'=>$nama,'poin'=>$poin,'jenis'=>$jenis,'ubah'=>wkt()),'id="'.$id.'"');
					eksyen('Data berhasil diubah','?hal=tatatertib');
				}else{
					$db->insert('tata_tertib',array('nama'=>$nama,'poin'=>$poin,'jenis'=>$jenis,'ubah'=>wkt()));
					$res = $db->getResult();
					eksyen('Data berhasil diinput','?hal=tatatertib');
				}
			}
			?>
			<form action="" method="POST" class="form-horizontal" role="form">
				<?php if(isset($_GET['id'])){ ?>
				<input type="hidden" name="id" id="inputId" class="form-control" value="<?=$_GET['id'];?>">
				<?php } ?>

				<div class="form-group">
					<label for="inpuKode" class="col-sm-2 control-label">Kode Tata Tertib :</label>
					<div class="col-sm-10">
						<input type="text" name="kode" id="inputKode" class="form-control" <?php if(isset($_GET['id'])){ ?>value="TR-0<?=$d[0]['id'];?>"<?php } ?> readonly maxlength="150">
					</div>
				</div>

				<div class="form-group">
					<label for="inpuKode" class="col-sm-2 control-label">Nama Tata Tertib :</label>
					<div class="col-sm-10">
						<input type="text" name="nama" id="inputKode" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['nama'];?>"<?php } ?> required="required" maxlength="150">
					</div>
				</div>

				<div class="form-group">
					<label for="inpuKode" class="col-sm-2 control-label">Jenis Tata Tertib :</label>
					<div class="col-sm-10">
						<select name="jenis" id="inputJenis" class="form-control" required="required">
							<option value="Ringan" <?php if(isset($_GET['id'])){ selek($d[0]['jenis'],"Ringan"); } ?>>Ringan</option>
							<option value="Sedang" <?php if(isset($_GET['id'])){ selek($d[0]['jenis'],"Sedang"); } ?>>Sedang</option>
							<option value="Berat" <?php if(isset($_GET['id'])){ selek($d[0]['jenis'],"Berat"); } ?>>Berat</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="inputNama" class="col-sm-2 control-label">Poin :</label>
					<div class="col-sm-3">
						<input type="text" name="poin" id="inputNama" class="form-control" <?php if(isset($_GET['id'])){ ?>value="<?=$d[0]['poin'];?>"<?php } ?> required="required" <?=angka();?> maxlength="3">
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
			echo '<h1 class="page-header">Ubah Data Tata Tertib</h1>Processing...';
			$id = mysql_real_escape_string($_GET['id']);
			if($_GET['a']==1){
				$db->update('tata_tertib',array('aktif'=>'0'),"id='$id'"); 
			}else{
				$db->update('tata_tertib',array('aktif'=>'1'),"id='$id'"); 
			}
			
			$res = $db->getResult();
			eksyen('','?hal=tatatertib');
			break;
		
		default:
			eksyen('Halaman tidak ditemukan','index.php');
			break;
	}
}