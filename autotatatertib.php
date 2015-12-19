<?php
include 'db.php';
include 'fungsi.php';
$idtata = $_GET['idtata'];

$db = new Database();
$db->connect();
$db->select('tata','*',null,"id='$idtata'");	// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $db->getResult();

foreach ($res as $d) {
	echo $d['nama'];
}
?>