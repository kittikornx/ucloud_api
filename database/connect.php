<?php
if (!session_id()) session_start();
$user = 'root';
$pass = '';
$tb = $_COOKIE['org_data'];

$dbcon = mysqli_connect('localhost', $user, $pass, $tb) or die('not connect' . mysqli_error());
