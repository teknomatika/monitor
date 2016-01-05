<div class="col-lg-12">
    <h1 class="page-header" id="header">Laporan Monitoring</h1>
</div>
<form action="" method="POST" class="form-inline" role="form">

	<div class="form-group lead">
		Pilih Tanggal
	</div>

	<div class="form-group">
		<label class="sr-only" for=""></label>
		<input type="text" name="tanggal" id="datepicker" class="form-control" value="" required="required">
	</div>

	<div class="form-group">
		<label class="sr-only" for="">Tahun Pelajaran</label>
		<input type="text" name="tanggal2" id="datepicker2" class="form-control" value="" required="required">
	</div>

	<button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

<?php
if(isset($_POST['tanggal'])){
	$tanggal = $db->escapeString($_POST['tanggal']);
	$tanggal2 = $db->escapeString($_POST['tanggal2']);

	// cari jumlah pelanggarannya
	$db->sql("select count(p.idtata) as jumlah, t.nama as nama_pelanggaran from pelanggaran p join tata_tertib t on t.id=p.idtata group by idtata");
	$dp = $db->getResult();
	$total2 = count($dp);
	$m = 1;

	// kosongkan data jika tidak ada
	if($total2<1){
		echo "<p>&nbsp;</p><div class=\"alert alert-info\" role=\"alert\">Tidak ada data yang dapat ditampilkan</div>";
	}else{
?>
<div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-center">Grafik Pelanggaran<br><?=TanggalIndo($tanggal);?> s/d <?=TanggalIndo($tanggal2);?></h3>
	<canvas id="myChart" heigth="400"></canvas>
</div>
<div class="col-lg-10 col-lg-offset-1">
	<h3 class="text-center">Tabel Pelanggaran</h3>
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th class="col-lg-1 text-center">No</th>
				<th>Jenis Pelanggaran</th>
				<th class="col-lg-2 text-center">Jumlah Pelanggaran</th>
			</tr>
		</thead>
		<tbody>
		  <?php
		  $o = 1;
		  foreach($dp as $dt){
		  ?>
			<tr>
				<td class="text-center"><?=$o++;?></td>
				<td><?=$dt['nama_pelanggaran'];?></td>
				<td class="text-center"><?=$dt['jumlah'];?></td>
			</tr>
		  <?php $o++; } ?>
		</tbody>
	</table>
</div>
<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var barChartData = {
		labels : [<?php foreach($dp as $d){ echo "\"Pelanggaran ".$d['nama_pelanggaran']."\""; if($m!=$total2){ echo ",";} $m++; } ?>],
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