<div class="col-lg-12">
    <h1 class="page-header">Laporan Jurusan</h1>
</div>
<form action="" method="POST" class="form-inline" role="form">

	<div class="form-group lead">
		Pilih Jurusan
	</div>

	<div class="form-group">
		<label class="sr-only" for="">Jurusan</label>
		<select name="jurusan" id="inputKelas" class="form-control" required="required">
			<?php
			$db->select('jurusan','*',null,"aktif='1'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$jb = $db->getResult();
			foreach($jb as $jb){
			?>
			<option value="<?=$jb['id'];?>"><?=$jb['nama'];?></option>
			<?php } ?>
		</select>
	</div>

	<div class="form-group">
		<label class="sr-only" for="">Tahun Pelajaran</label>
		<select name="tapel" id="inputKelas" class="form-control" required="required">
			<?php
			$db->select('tapel','*',null,"aktif='1'",'awal asc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$jb = $db->getResult();
			foreach($jb as $jb){
			?>
			<option value="<?=$jb['id'];?>">Tapel <?=$jb['awal'];?> / <?=$jb['akhir'];?></option>
			<?php } ?>
		</select>
	</div>

	<button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

<?php
if(isset($_POST['jurusan'])){
	$jurusan = $db->escapeString($_POST['jurusan']);
	$tapel = $db->escapeString($_POST['tapel']);

	// cari kelas
	$db->sql("select s.kelas as kelas from siswa s 
				join tapel t on t.id=s.tapel
				join kelas k on k.id=s.kelas
				join jurusan j on j.id=s.jurusan
				where t.id='$tapel' and j.id='$jurusan' and t.aktif='1'
				group by s.kelas
				order by j.nama asc
			");
	$ds = $db->getResult();
	$total = count($ds);
	$n = 1;

	// cari jumlah pelanggarannya
	$db->sql("select count(idsiswa) as jumlah from pelanggaran p
				join siswa s on s.nis=p.idsiswa
				join tapel t on t.id=s.tapel
				join jurusan j on j.id=s.jurusan
				where t.id='$tapel' and j.id='$jurusan' and t.aktif='1'
				order by j.nama asc
			");
	$dp = $db->getResult();
	$total2 = count($dp);
	$m = 1;

	// kosongkan data jika tidak ada
	if($total<1){
		echo "<p>&nbsp;</p><div class=\"alert alert-info\" role=\"alert\">Tidak ada data yang dapat ditampilkan</div>";
	}else{
?>
<div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-center">Grafik <br>Jurusan <?=konvert('jurusan',$jurusan,'nama');?> (<?=konvert('tapel',$tapel,'awal');?>/<?=konvert('tapel',$tapel,'akhir');?>)</h3>
	<canvas id="myChart" heigth="400"></canvas>
</div>
<div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-center">Tabel <br>Jurusan <?=konvert('jurusan',$jurusan,'nama');?> (<?=konvert('tapel',$tapel,'awal');?>/<?=konvert('tapel',$tapel,'akhir');?>)</h3>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th class="col-lg-1 text-center">No</th>
				<th>Nama Kelas</th>
				<th class="col-lg-2 text-center">Jumlah Pelanggaran</th>
				<th class="col-lg-1 text-center">#</th>
			</tr>
		</thead>
		<tbody>
		  <?php
		  $o = 1;
		  foreach($ds as $dt){
		  ?>
			<tr>
				<td class="text-center"><?=$o++;?></td>
				<td><?=konvert('kelas',$dt['kelas'],'nama');?></td>
				<td class="text-center"><?=cekpelanggarankelas($dt['kelas']);?></td>
				<td class="text-center"><form action="?hal=laporan_kelas" method="post"><input type="hidden" name="kelas" value="<?=$dt['kelas'];?>"><input type="hidden" name="tapel" value="<?=$tapel;?>"><button type="submit" class="btn btn-primary btn-xs">Detail</button></form></td>
			</tr>
		  <?php $o++; } ?>
		</tbody>
	</table>
</div>
<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var barChartData = {
		labels : [<?php foreach($ds as $d){ echo "\"Kelas ".konvert('kelas',$d['kelas'],'nama')."\""; if($n!=$total){ echo ",";} $n++; } ?>],
		datasets : [
			{
				fillColor : "rgba(220,220,220,0.5)",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [<?php foreach($dp as $p){ echo "\"".$p['jumlah']."\""; if($m!=$total2){ echo ",";} $m++; } ?>]
			}
		]
	}
	window.onload = function(){
		var ctx = document.getElementById("myChart").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}
	</script>
<?php
	}
}
?>