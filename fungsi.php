<?php
define("TITLE", "Monitoring");
define("NAMASEKOLAH","SMK Namanya");
date_default_timezone_set("Asia/Jakarta");

function konvert($tabel,$id,$kolom){
	$q = mysql_query("select $kolom from $tabel where id='$id'");
	$d = mysql_fetch_array($q);
	return $d[$kolom];
}

function getnim(){
	$db->select('siswa');
	$res = $db->getResult();
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

function laktif($id=array()){

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