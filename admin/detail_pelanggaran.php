<?php 
if(!isset($_GET['nis']) or $_GET['nis']==""){
	eksyen('','?hal=pelanggaran');
}

$nis = $_GET['nis'];
$db->select('pelanggaran','*',null,"idsiswa='$nis'",'tanggal desc');
	//$jum = $db->numRows(); if($jum<1){ eksyen('','?hal=pelanggaran'); }	// jika tidak ada pelanggaran dengan nis tersebut
$res = $db->getResult();
?>
<div class="row">
	<div class="col-lg-12">
	    <h1 class="page-header">Detail Pelanggaran <small>| <a href="?hal=<?=$_GET['ref'];?>">Kembali</a></small></h1>
	</div>
</div>
<div class="row">
	<div class="col-lg-5 text-right lead">
		NIS:<br>Nama Siswa:<br>Nama Orang Tua:<br>No HP Orang Tua:
	</div>
	<div class="col-lg-4 lead">
		<?=$nis;?><br><?=konvert2('siswa','nis',$nis,'nama');?><br><?=konvert2('siswa','nis',$nis,'nama_ortu');?><br><?=konvert2('siswa','nis',$nis,'hp_ortu');?>
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
			  <?php $poin=0; $i=1; foreach($res as $d){ ?>
				<tr class="<?php if(konvert('tata_tertib',$d['idtata'],'jenis')=='Ringan'){echo "info";}elseif(konvert('tata_tertib',$d['idtata'],'jenis')=='Sedang'){echo "warning";}elseif(konvert('tata_tertib',$d['idtata'],'jenis')=='Berat'){echo "danger";} ?>">
					<td class="text-center"><?=$i;?></td>
					<td class="text-center" title="<?=time_ago($d['tanggal']);?>"><?=TanggalIndo($d['tanggal']);?></td>
					<td class="text-center">TR-0<?=$d['idtata'];?></td>
					<td><?=konvert('tata_tertib',$d['idtata'],'nama');?></td>
					<td class="text-center"><?=konvert('tata_tertib',$d['idtata'],'poin');?><?php $poin = konvert('tata_tertib',$d['idtata'],'poin');?></td>
				</tr>
			  <?php $i++; $poin=$poin+$poin; } ?>
			</tbody>
			<tfoot>
				<tr class="info lead">
					<td colspan="4" class="text-right">Total Poin</td>
					<td class="text-center"><?=$poin;?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>