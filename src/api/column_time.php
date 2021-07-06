<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

$stmt = $db->prepare('SELECT study_date, sum(total_hour) AS total_hour FROM study_hours_posts WHERE user_id=? AND study_date LIKE ? GROUP BY study_date');
$stmt->execute(array(
  $_SESSION['user_id'],
  "{$_GET['month']}%"
));

foreach($stmt->fetchAll() as $index => $column_time_data) {
  $date = substr($column_time_data['study_date'], -2);
  $column_time[$index]['date'] = $date;
  $column_time[$index]['hour'] = $column_time_data['total_hour'];
  $column_time[$index]['color'] = '#0f71bc';
}

echo json_encode($column_time);
