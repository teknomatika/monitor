<?php 
if(!isset($_GET['nis']) or $_GET['nis']==""){
	eksyen('','?hal=pelanggaran');
}

$nis = $_GET['nis'];
$db->select('pelanggaran','*',null,"idsiswa='$nis'",'tanggal desc');
	$jum = $db->numRows(); if($jum<1){ eksyen('','?hal=pelanggaran'); }	// jika tidak ada pelanggaran dengan nis tersebut
$res = $db->getResult();
?>
<div class="row">
	<div class="col-lg-12">
	    <h1 class="page-header">Detail Pelanggaran <small>| <a href="?hal=pelanggaran">Kembali</a></small></h1>
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
					<th class="text-center col-lg-2">Tanggal</th>
					<th class="text-center col-lg-2">ID Pelanggaran</th>
					<th>Pelanggaran</th>
					<th class="text-center col-lg-2">Poin</th>
				</tr>
			</thead>
			<tbody>
			  <?php $i=1; foreach($res as $d){ ?>
				<tr>
					<td class="text-center"><?=$i;?></td>
					<td class="text-center" title="<?=time_ago($d['tanggal']);?>"><?=TanggalIndo($d['tanggal']);?></td>
					<td class="text-center">TR-0<?=$d['idtata'];?></td>
					<td><?=konvert('tata',$d['idtata'],'nama');?></td>
					<td class="text-center"><?=konvert('tata',$d['idtata'],'poin');?></td>
				</tr>
			  <?php $i++; } ?>
			</tbody>
		</table>
	</div>
</div>