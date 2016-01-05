<?php
session_start();
include 'db.php';
include 'fungsi.php';
$db = new Database();
$db->connect();

$user = $db->escapeString($_POST['user']);
$pass = $db->escapeString($_POST['pass']);
$md5pass = md5($pass);

$db->select('users','*',NULL,"username='$user' and password='$md5pass'");
$data = $db->getResult();

if($data){
    $db->update('users',array('terakhir_login'=>wkt()),"username='$user' and password='$md5pass'")
    $_SESSION['username'] = $data[0]['username'];
    $_SESSION['userid'] = $data[0]['id'];
    switch ($data[0]['level']) {
        case 'Admin':
            $_SESSION['level'] = 'Admin';
            break;

        case 'Kepsek':
            $_SESSION['level'] = 'Kepsek';
            break;
        
        default:
            unset($_SESSION['username']);
            unset($_SESSION['userid']);
            unset($_SESSION['level']);
            break;
    }
    eksyen('Selamat datang, '. $data[0]['username'].' sebagai '.$data[0]['level'],'.');
}else{
    eksyen('User tidak ditemukan','.');
}