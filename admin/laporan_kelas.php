<div class="col-lg-12">
    <h1 class="page-header">Laporan Kelas</h1>
</div>
<form action="" method="POST" class="form-inline" role="form">

	<div class="form-group lead">
		Pilih Kelas
	</div>

	<div class="form-group">
		<label class="sr-only" for="">Kelas</label>
		<select name="kelas" id="inputKelas" class="form-control" required="required">
			<?php
			$db->select('kelas','*',null,"aktif='1' and nama!='Alumni'",null); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
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
			$db->select('tapel','*',null,"aktif='1'",'tapel_awal asc'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$jb = $db->getResult();
			foreach($jb as $jb){
			?>
			<option value="<?=$jb['id'];?>"><?=$jb['tapel_awal'];?> / <?=$jb['tapel_akhir'];?></option>
			<?php } ?>
		</select>
	</div>

	<button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

<?php
if(isset($_POST['kelas'])){
	$kelas = $db->escapeString($_POST['kelas']);
	$tapel = $db->escapeString($_POST['tapel']);

	// cari siswa
	$db->sql("select s.nama as namasiswa from siswa s 
				join tapel t on t.id=s.tapel
				join kelas k on k.id=s.kelas
				where t.id='$tapel' and k.id='$kelas'
				order by s.nama asc
			");
	$ds = $db->getResult();
	$total = count($ds);
	$n = 1;

	// cari jumlah pelanggarannya
	$db->sql("select count(idsiswa) as jumlah from pelanggaran p
				join siswa s on s.nis=p.idsiswa
				join tapel t on t.id=s.tapel
				join kelas k on k.id=s.kelas
				where t.id='$tapel' and k.id='$kelas'
				order by s.nama asc
			");
	$dp = $db->getResult();
	$total2 = count($dp);
	$m = 1;

	// data tabel pelanggaran
	$db->sql("select s.nis as nis from siswa s 
				join tapel t on t.id=s.tapel
				join kelas k on k.id=s.kelas
				where t.id='$tapel' and k.id='$kelas'
				order by s.nama asc
			");
	$dt = $db->getResult();

	// kosongkan data jika tidak ada
	if($total<1){
		echo "<p>&nbsp;</p><div class=\"alert alert-info\" role=\"alert\">Tidak ada data yang dapat ditampilkan</div>";
	}else{
?>
<div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-center">Grafik <br>Kelas <?=konvert('kelas',$kelas,'nama');?> (<?=konvert('tapel',$tapel,'tapel_awal');?>/<?=konvert('tapel',$tapel,'tapel_akhir');?>)</h3>
	<canvas id="myChart" heigth="400"></canvas>
</div>
<div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-center">Tabel <br>Kelas <?=konvert('kelas',$kelas,'nama');?> (<?=konvert('tapel',$tapel,'tapel_awal');?>/<?=konvert('tapel',$tapel,'tapel_akhir');?>)</h3>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th class="col-lg-1 text-center">No</th>
				<th>Nama Siswa</th>
				<th class="col-lg-2 text-center">Jumlah Pelanggaran</th>
				<th class="col-lg-1 text-center">Poin</th>
			</tr>
		</thead>
		<tbody>
		  <?php
		  $o = 1;
		  foreach($dt as $dt){
		  ?>
			<tr>
				<td class="text-center"><?=$o++;?></td>
				<td><a href="?hal=detail_pelanggaran&nis=<?=$dt['nis'];?>&ref=<?=$_GET['hal'];?>"><?=konvert2('siswa','nis',$dt['nis'],'nama');?></a></td>
				<td class="text-center"><?=cekpelanggaran($dt['nis']);?></td>
				<td class="text-center"><?=cekpoin($dt['nis']);?></td>
			</tr>
		  <?php $o++; } ?>
		</tbody>
	</table>
</div>
<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var barChartData = {
		labels : [<?php foreach($ds as $d){ echo "\"".$d['namasiswa']."\""; if($n!=$total){ echo ",";} $n++; } ?>],
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