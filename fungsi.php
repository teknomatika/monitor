<?php
define("TITLE", "Monitoring");
define("NAMASEKOLAH","SMK YUPPENTEK 1 TANGERANG");
date_default_timezone_set("Asia/Jakarta");

function konvert($tabel,$id,$kolom){
	$q = mysql_query("select $kolom from $tabel where id='$id'");
	$d = mysql_fetch_array($q);
	return $d[$kolom];
}

function konvert2($tabel,$key,$val,$kolom){
	$q = mysql_query("select $kolom from $tabel where $key='$val'");
	$d = mysql_fetch_array($q);
	return $d[$kolom];
}

function getnis($jur){

	// menentukan jumlah siswa
	$qs = mysql_query("select id from siswa");
	$data = mysql_fetch_array($qs);
	$jum = mysql_num_rows($qs);

	// untuk menentukan nim akhir
	if($jum<1){
		$id = 1;
	}else{
		$id = $jum+1;
	}

	// tahun
	$thn = date('y');

	// menentukan kode jurusan
	$jur = konvert('jurusan',$jur,'kode');

	return $thn.$jur.$id;	
}

function hom(){
	if(!isset($_GET['hal'])){
		echo ' class="active"';
	}
}

function aktif($id){
	if(isset($_GET['hal'])){
		if($_GET['hal']==$id){
			echo ' class="active"';
		}
	}
}

function laktif($id){
	if(isset($_GET['hal'])){
		if($_GET['hal']==$id){
			echo ' active';
		}
	}
}

function hitungpoin($nis,$idpel,$opsi){
	// ambil poin dari pelanggaran
	$q = mysql_query("select t.poin as poin from pelanggaran p join tata t on t.id=p.idtata where p.id='$idpel'");
	$d = mysql_fetch_array($q);
	$poin = $d['poin'];

	// tambah/kurangkan poin ke tabel poin berdasarkan nis
	if($opsi=="tambah"){
		mysql_query("update poin set poin=poin+'$poin', ubah=now() where nis='$nis'");
	}elseif($opsi=="kurang"){
		mysql_query("update poin set poin=poin-'$poin', ubah=now() where nis='$nis'");
	}else{
		eksyen('Salah Fungsi!','index.php');
	}
}

function cekpoin($nis){
	// munculkan
	$qp = mysql_query("select poin from poin where nis='$nis'");
	$dp = mysql_fetch_array($qp);
	return $dp['poin'];
}

function cektindak($nis){

	// cek masuk sanksi yang manakan ia
	$poin = cekpoin($nis);

	$q = mysql_query("select * from sanksi order by minimal desc");
	while($d = mysql_fetch_array($q)){
		$id = $d['id'];
		$sql = "select true from sanksi where '$poin'>=minimal and '$poin'<=maksimal and id='$id'";
		$q2 = mysql_query($sql);
		$d2 = mysql_fetch_array($q2);
		$cek = $d2['TRUE'];
		if($cek == 1){
			// input ke tabel tindak
			$uuid = id();
			mysql_query("insert into tindak(id,idsiswa,idsanksi,buat) values('$uuid','$nis','$id',now())");
		}
	}
}

function belumtindak(){
	$q = mysql_query("select * from tindak where tindak='0'");
	$jum = mysql_num_rows($q);
	if($jum>=1){
		return "<span class=\"badge\">$jum</span>";
	}
}

function angka(){
	echo 'onkeypress="return isNumber(event)"';
}

function id(){
	$q = mysql_query("select uuid() as id");
	$d = mysql_fetch_array($q);
	return $d['id'];
}

function wkt(){
	$q = mysql_query("select now() as id");
	$d = mysql_fetch_array($q);
	return $d['id'];
}

function sesi($grup){
	if($_SESSION['grup'] != $grup){
		echo '<script>window.location.assign("inside.php");</script>';
	}
}

function cekbok($a,$b){
	if($a==$b){
		echo "checked";
	}
}

function selek($a,$b){
	if($a==$b){
		echo "selected";
	}
}

function yakin(){
	echo "onClick=\"return confirm('Apakah Anda yakin akan melakukan aksi ini?');\" ";
}

function eksyen($teks=false,$tujuan){ // buat pindah halaman
	if($teks){
		die("<script>alert('$teks');</script><script>window.location.assign('$tujuan');</script>");
	}else{
		die("<script>window.location.assign('$tujuan');</script>");
	}
}

function tbl_tambah($kata,$url){
	echo '<a href="'.$url.'" class="btn btn-primary pull-right" alt="Tambah" title="Tambah"><i class="fa fa-plus fa-fw"></i> '.$kata.'</a>';
}

function tbl_ubah($url){
	echo '<a href="'.$url.'" class="btn btn-info btn-xs" alt="Ubah" title="Ubah"><i class="fa fa-edit fa-fw"></i></a>';
}

function tbl_hapus($url){
	echo '<a href="'.$url.'" class="btn btn-danger btn-xs" onClick="return confirm(\'Apakah Anda yakin akan melakukan aksi ini?\')"  alt="Hapus" title="Hapus"><i class="fa fa-trash-o fa-fw"></i></a>';
}

function tanggal($tgl){
	$date = new DateTime($tgl);
	return $date->format('D, d M Y');	// ('D, d M Y H:i:s');
}

function TanggalIndo($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);
 
	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
	return($result);
}

function time_ago( $date )
{
    if( empty( $date ) )
    {
        return "No date provided";
    }

    $periods = array("detik", "menit", "jam", "hari", "minggu", "bulan", "tahun", "dekade");

    $lengths = array("60","60","24","7","4.35","12","10");

    $now = time();

    $unix_date = strtotime( $date );

    // check validity of date

    if( empty( $unix_date ) )
    {
        return "Bad date";
    }

    // is it future date or past date

    if( $now > $unix_date )
    {
        $difference = $now - $unix_date;
        $tense = "yang lalu";
    }
    else
    {
        $difference = $unix_date - $now;
        $tense = "dari sekarang";
    }

    for( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
    {
        $difference /= $lengths[$j];
    }

    $difference = round( $difference );

    if( $difference != 1 )
    {
        //$periods[$j].= "s";
        $periods[$j].= "";
    }

    return "$difference $periods[$j] {$tense}";
}