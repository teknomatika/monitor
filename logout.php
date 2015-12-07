<?php session_start();
include 'db.php';
include 'fungsi.php';
unset($_SESSION['username']);
unset($_SESSION['userid']);
unset($_SESSION['level']);
eksyen('Good bye!','index.php');