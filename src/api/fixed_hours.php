<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

$array = array();

$stmt = $db->prepare('SELECT sum(total_hour) FROM study_hours_posts WHERE user_id=?');
$stmt->execute(array($_SESSION['user_id']));
$array['total_hour'] = round($stmt->fetchAll()[0][0]);

$today = date("Y-m-d");
$stmt = $db->prepare('SELECT sum(total_hour) FROM study_hours_posts WHERE user_id=? AND study_date=?');
$stmt->execute(array(
  $_SESSION['user_id'],
  $today
));

$array['today_hour'] = round($stmt->fetchAll()[0][0]);

echo json_encode($array);