<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

$stmt = $db->prepare('SELECT sum(total_hour) FROM study_hours_posts WHERE user_id=? AND study_date LIKE ?');
$stmt->execute(array(
  $_SESSION['user_id'],
  "{$_GET['month']}%"
));

$month_hour = round($stmt->fetchAll()[0][0]);

echo $month_hour;