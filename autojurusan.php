<?php
include 'db.php';
include 'fungsi.php';
$nis = $_GET['nis'];

$db = new Database();
$db->connect();
$db->select('siswa','*',null,"nis='$nis'");	// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $db->getResult();

foreach ($res as $d) {
	echo konvert('jurusan',$d['jurusan'],'nama');
}
?>