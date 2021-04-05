<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

$stmt = $db->prepare('SELECT MIN(contents.id) AS id, SUM(content_posts.hour) AS hour, contents.content, MIN(contents.color_code) AS color_code FROM study_hours_posts JOIN content_posts JOIN contents ON study_hours_posts.id = content_posts.study_hours_post_id AND content_posts.content_id = contents.id WHERE study_hours_posts.user_id=? AND study_hours_posts.study_date LIKE ? GROUP BY contents.content ORDER BY MIN(contents.id)');
$stmt->execute(array(
  $_SESSION['user_id'],
  "{$_GET['month']}%"
));

for ($i = 0; $i < 3; $i++) {
  $pie_contents[$i]['hour'] = 0;
  $pie_contents[$i]['content'] = '';
  $pie_contents[$i]['color_code'] = '';
}

foreach ($stmt->fetchAll() as $index => $pie_contents_data) {
  $pie_contents[$pie_contents_data['id'] - 1]['hour'] = round((float)$pie_contents_data['hour'], 2);
  $pie_contents[$pie_contents_data['id'] - 1]['content'] = $pie_contents_data['content'];
  $pie_contents[$pie_contents_data['id'] - 1]['color_code'] = $pie_contents_data['color_code'];
}

echo json_encode($pie_contents);