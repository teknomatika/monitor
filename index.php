<?php session_start();?>
<!DOCTYPE html>
<?php
include 'db.php';
include 'fungsi.php';
$db = new Database();
$db->connect();
?>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=TITLE;?> | <?=NAMASEKOLAH;?></title>

		<!-- Bootstrap CSS -->
		<link href="css/cerulean-bootstrap.min.css" rel="stylesheet">
		<link href="css/carousel.css" rel="stylesheet">
		 <link href="components/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		 <link href="components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
		 <link href="components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
		 <link href="components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery -->
		<script src="js/jquery.js"></script>
		<script src="components/jquery-ui/jquery-ui.min.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		<!-- Another JavaScript -->
		<script src="js/isNumber.js"></script>
		<script src="js/Chart.min.js"></script>
		<script src="components/datatables/media/js/jquery.dataTables.min.js"></script>
    	<script src="components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
	</head>
	<body>
	<div class="navbar-wrapper">
      <div class="container">
      	<header role="banner">
      	  <?php if(isset($_GET['hal'])){ ?>
  			<img id="logo-main" src="images/logo.jpg" width="100%" alt="Logo Thing main logo">
  		  <?php } ?>

	        <nav class="navbar navbar-inverse navbar-static-top">
	          <div class="container">
	            <div class="navbar-header">
	              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	              </button>
	              <!-- <a class="navbar-brand" href="."><?=NAMASEKOLAH;?></a> -->
	            </div>
	            <div id="navbar" class="navbar-collapse collapse">
	              <?php if(isset($_SESSION['username'])){ ?>
	              <ul class="nav navbar-nav">
	                <li <?=hom();?>><a href=".">Home</a></li>
	                <li <?=aktif('siswa');?>><a href="?hal=siswa">Data Siswa</a></li>
	                <li <?=aktif('tatatertib');?>><a href="?hal=tatatertib">Tata Tertib</a></li>
	                <li <?=aktif('sanksi');?>><a href="?hal=sanksi">Sanksi</a></li>
	                <li <?=aktif('pelanggaran');?><?=aktif('detail_pelanggaran');?>><a href="?hal=pelanggaran">Pelanggaran</a></li>
	                <li <?=aktif('penindakan');?><?=aktif('detail_peringatan');?>><a href="?hal=penindakan">Peringatan <?=belumtindak();?></a></li>
	                <li class="dropdown<?=laktif('laporan_kelas');?><?=laktif('laporan_jurusan');?><?=laktif('laporan_tingkat');?><?=laktif('laporan_monitoring');?>">
	                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Laporan <span class="caret"></span></a>
	                  <ul class="dropdown-menu">
	                    <li <?=aktif('laporan_kelas');?>><a href="?hal=laporan_kelas">Kelas</a></li>
	                    <li <?=aktif('laporan_jurusan');?>><a href="?hal=laporan_jurusan">Jurusan</a></li>
	                    <li <?=aktif('laporan_tingkat');?>><a href="#">Tingkat</a></li>
	                    <li role="separator" class="divider"></li>
	                    <!--li class="dropdown-header">Nav header</li-->
	                    <li <?=aktif('laporan_monitoring');?>><a href="?hal=laporan_monitoring#header">Monitoring</a></li>
	                  </ul>
	                </li>
	                <li class="dropdown">
	                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Pengaturan <span class="caret"></span></a>
	                  <ul class="dropdown-menu">
	                    <li <?=aktif('tapel');?>><a href="?hal=tapel"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Tahun Pelajaran</a></li>
	                    <li <?=aktif('jurusan');?>><a href="?hal=jurusan"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Jurusan</a></li>
	                    <li <?=aktif('kelas');?>><a href="?hal=kelas"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Kelas</a></li>
	                    <li <?=aktif('pengguna');?>><a href="?hal=pengguna"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Master Data Pengguna</a></li>
	                  </ul>
	                </li>
	              </ul>
	              <ul class="nav navbar-nav navbar-right">
	                <li class="dropdown">
	                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?=konvert('users',$_SESSION['userid'],'nama');?> <span class="caret"></span></a>
	                  <ul class="dropdown-menu">
	                    <li><a href="?hal=profil"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah Profil</a></li>
	                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
	                  </ul>
	                </li>
	              </ul>
	            <?php }else{ ?>
		            <form class="navbar-form navbar-right" action="login.php" method="post">
						<div class="form-group">
							<input type="text" class="form-control" name="user" placeholder="Username">
							<input type="password" class="form-control" name="pass" placeholder="Password">
						</div>
						<button type="submit" class="btn btn-primary">Masuk</button>
						<a href="?hal=lupa" class="btn btn-default">Lupa Password?</a>
					</form>
				<?php } ?>
	            </div>
	          </div>
	        </nav>
     	</header>
      </div>
    </div>

	    <?php
	    if(isset($_GET['hal'])){
	    	echo '<div class="container badan">';
	    	require_once 'admin/'.$_GET['hal'].'.php';
	    	echo "</div>";
	    }else{
	    ?>
	    <!-- Carousel
	    ================================================== -->
	    <div id="myCarousel" class="carousel slide" data-ride="carousel">
	      <!-- Indicators -->
	      <ol class="carousel-indicators">
	        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	        <li data-target="#myCarousel" data-slide-to="1"></li>
	      </ol>
	      <div class="carousel-inner" role="listbox">
	        <div class="item active">
	          <img class="first-slide" src="images/visi-misi.jpg" alt="First slide">
	          <div class="container">
	            <div class="carousel-caption">
	              <h1>Visi</h1>
	              <p>Menjadi sekolah pilihan masyarakat dan menghasilkan lulusanyang dapat beradaptasi serta eksis di lingkungannya dan masyarakat global</p>
	              <!--p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p-->
	            </div>
	          </div>
	        </div>
	        <div class="item">
	          <img class="second-slide" src="images/misi-visi.jpg" alt="Second slide">
	          <div class="container">
	            <div class="carousel-caption">
	              <h1>Misi</h1>
	              <p>Membekali peserta didik sehingga memiliki kompetensi yang meliputi :</p>
	              <ol>
	              	<li>Mengelola kemampuan diri dan orang lain</li>
	              	<li>Keterampilan menyelesaikan pekerjaan dan tugas</li>
	              	<li>Cakap berkomunikasi dan beradaptasi</li>
	              	<li>Kemampuan memobilisasi inovasi dan perubahan</li>
	              	<li>Tanggap terhadap IPTEK dan lingkungannya</li>
	              	<li>Memiliki moral dan budi pekerti luhur berdasarkan agama</li>
	              </ol>
	            </div>
	          </div>
	        </div>
	      </div>
	      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
	        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	        <span class="sr-only">Previous</span>
	      </a>
	      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
	        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	        <span class="sr-only">Next</span>
	      </a>
	    </div><!-- /.carousel -->
	    <?php } ?>



    	<!-- load JavaScript -->
    	<script>
	    $(document).ready(function() {
	        $('#tbl,#tbl2,#tbl3').DataTable({
	                responsive: true
	        });

	        $( "#datepicker,#datepicker2" ).datepicker({
	        	changeYear: true,
	        	changeMonth: true,
	        	dateFormat:"yy-mm-dd"
	        });

	        // js Pelanggaran
	        var daftarnis = [
	        	<?php
	        	$db = new Database();
				$db->connect();
				$db->select('siswa','nis');	// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$res = $db->getResult();
				foreach ($res as $d) {
					echo "\"".$d['nis']."\",";
				}
				?>
	        ];

	        $("#nis").autocomplete({
	        	source: daftarnis
	        });

	        var daftartatatertib = [
	        	<?php
	        	$db = new Database();
				$db->connect();
				$db->select('tata_tertib','id');	// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$res = $db->getResult();
				foreach ($res as $d) {
					echo "\"".$d['id']."\",";
				}
				?>
	        ];

	        $("#idtata").autocomplete({
	        	source: daftartatatertib
	        });

	        $("#nis").change(function(){
			    var nis = $('#nis').val();
			    $.ajax({
			        url: "autonama.php",
			        data: "nis="+nis,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#nama").val(msg);
			        }
			    });
			    $.ajax({
			        url: "autokelas.php",
			        data: "nis="+nis,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#kelas").val(msg);
			        }
			    });
			    $.ajax({
			        url: "autojurusan.php",
			        data: "nis="+nis,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#jurusan").val(msg);
			        }
			    });
			    $.ajax({
			        url: "autowali.php",
			        data: "nis="+nis,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#wali").val(msg);
			        }
			    });
			    $.ajax({
			        url: "autofoto.php",
			        data: "nis="+nis,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#foto").html(msg);
			        }
			    });
			});

			$("#idtata").change(function(){
			    var idtata = $('#idtata').val();
			    $.ajax({
			        url: "autotatatertib.php",
			        data: "idtata="+idtata,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#tatatertib").val(msg);
			        }
			    });
			    $.ajax({
			        url: "autopoin.php",
			        data: "idtata="+idtata,
			        cache: false,
			        success: function(msg){
			            //jika data sukses diambil dari server kita tampilkan
			            //di <select id=kel>
			            $("#poin").val(msg);
			        }
			    });
			});

	    });
	    </script>
	</body>
</html>