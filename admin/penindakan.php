<?php if(!isset($_GET['detail'])){ ?>
<div class="col-lg-12">
    <h1 class="page-header">Data Peringatan</h1>
</div>
<table class="table table-hover table-bordered" id="tbl">
	<thead>
		<tr>
			<th class="col-lg-1 text-center">No</th>
			<th class="col-lg-1 text-center">NIS</th>
			<th class="col-lg-3">Nama</th>
			<th class="col-lg-1 text-center">Kelas</th>
			<th class="col-lg-4">Sanksi</th>
			<th class="col-lg-1 text-center">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$db = new Database();
		$db->connect();
		$db->select('tindak','*',null,null,'buat desc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
		$res = $db->getResult();
		foreach($res as $d){
		?>
		<tr<?php if($d['tindak']==0){ ?> class="danger"<?php } ?>>
			<td class="text-center"><?=$i++;?></td>
			<td class="text-center"><a href="?hal=detail_peringatan&nis=<?=$d['idsiswa'];?>"><?=$d['idsiswa'];?></a></td>
			<td><a href="?hal=detail_peringatan&nis=<?=$d['idsiswa'];?>"><?=konvert2('siswa','nis',$d['idsiswa'],'nama');?></a></td>
			<td class="text-center"><?php $idkelas = konvert2('siswa','nis',$d['idsiswa'],'kelas'); echo konvert('kelas',$idkelas,'nama');?></td>
			<td><?=konvert('sanksi',$d['idsanksi'],'nama');?></td>
			<td class="text-center"><a href="?hal=penindakan&detail=<?=$d['id'];?>" class="btn btn-primary btn-xs">Tindak</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<div class="alert alert-info" role="alert">Klik pada nama siswa atau NIS untuk melihat daftar peringatan.</div>

<?php 
}else{

	if(isset($_POST['idtindak'])){
		$idtindak = $db->escapeString($_POST['idtindak']);
		$status = $db->escapeString($_POST['status']);
		$db->update('tindak',array('tindak'=>$status,'ubah'=>wkt(),'idguru'=>$_SESSION['userid']),"id='$idtindak'");
		echo "Processing...";
		eksyen('Data berhasil diubah','?hal=penindakan');
	}

	$id = mysql_real_escape_string($_GET['detail']);
	$db->select('tindak','*',null,"id='$id'");
	$res = $db->getResult();
	foreach($res as $d){
?>

<div class="col-lg-12">
    <h1 class="page-header">Detail Tindakan <small>| <a href="?hal=penindakan">Kembali</a></small></h1>
</div>
<table class="table">
	<tbody>
		<tr>
			<td class="col-lg-2">NIS</td>
			<td><?=$d['idsiswa'];?></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td><?=konvert2('siswa','nis',$d['idsiswa'],'nama');?></td>
		</tr>
		<tr>
			<td>Total Poin</td>
			<td><?=cekpoin($d['idsiswa']);?></td>
		</tr>
		<tr>
			<td>Sanksi</td>
			<td><?=konvert('sanksi',$d['idsanksi'],'nama');?></td>
		</tr>		
		<form action="" method="post">
			<input type="hidden" name="idtindak" id="inputIdtindak" class="form-control" value="<?=$id;?>">
		<tr>
			<td>Status</td>
			<td>
				<div class="radio">
					<label>
						<input type="radio" name="status" id="inputJk" value="1" <?php cekbok($d['tindak'],'1');?>>
						Sudah Ditindak
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="status" id="inputJk" value="0" <?php cekbok($d['tindak'],'0');?>>
						Belum Ditindak
					</label>
				</div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><button type="submit" class="btn btn-primary">Simpan</button> <a href="?hal=penindakan" class="btn btn-default">Batal</a></td>
		</tr>
		</form>
	</tbody>
</table>

<?php 
	}
}
?>