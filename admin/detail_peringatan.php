<?php 
if(!isset($_GET['nis']) or $_GET['nis']==""){
	eksyen('','?hal=penindakan');
}

$nis = $_GET['nis'];
$db->select('tindak','*',null,"idsiswa='$nis'",'buat desc');
	$jum = $db->numRows(); if($jum<1){ eksyen('','?hal=penindakan'); }	// jika tidak ada pelanggaran dengan nis tersebut
$res = $db->getResult();
?>
<div class="row">
	<div class="col-lg-12">
	    <h1 class="page-header">Detail Peringatan <small>| <a href="?hal=penindakan">Kembali</a></small></h1>
	</div>
</div>
<div class="row">
	<div class="col-lg-4 col-lg-offset-5 lead">
		<?=$nis;?> <br> <?=konvert2('siswa','nis',$nis,'nama');?>
	</div>
	<div class="col-lg-3">
		<img src="<?=konvert2('siswa','nis',$nis,'foto');?>" height="150">
	</div>
</div>
<br>
<div class="row">
	<div class="col-lg-12">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th class="text-center col-lg-1">No</th>
					<th class="text-center col-lg-2">Tanggal Pelanggaran</th>
					<th>Jenis Peringatan</th>
					<th class="text-center col-lg-2">Tanggal Tindakan</th>
					<th class="text-center col-lg-2">Guru Penindak</th>
				</tr>
			</thead>
			<tbody>
			  <?php $i=1; foreach($res as $d){ ?>
				<tr<?php if($d['tindak']==0){ ?> class="danger"<?php } ?>>
					<td class="text-center"><?=$i;?></td>
					<td class="text-center" title="<?=time_ago($d['buat']);?>"><?=TanggalIndo($d['buat']);?></td>
					<td><?=konvert('sanksi',$d['idsanksi'],'nama');?></td>
					<td class="text-center" title="<?=time_ago($d['ubah']);?>"><?=TanggalIndo($d['ubah']);?></td>
					<td class="text-center"><?=konvert('users',$d['idguru'],'nama');?></td>
				</tr>
			  <?php $i++; } ?>
			</tbody>
		</table>
	</div>
</div>