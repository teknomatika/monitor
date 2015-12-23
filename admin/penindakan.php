<div class="col-lg-12">
    <h1 class="page-header">Data Penindakan</h1>
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
			<td class="text-center"><?=$d['idsiswa'];?></td>
			<td><?=konvert2('siswa','nis',$d['idsiswa'],'nama');?></td>
			<td class="text-center"><?php $idkelas = konvert2('siswa','nis',$d['idsiswa'],'kelas'); echo konvert('kelas',$idkelas,'nama');?></td>
			<td><?=konvert('sanksi',$d['idsanksi'],'nama');?></td>
			<td class="text-center"><a href="?hal=penindakan&detail=<?=$d['id'];?>" class="btn btn-primary btn-xs">Detail</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>