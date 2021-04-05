<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

$stmt = $db->prepare('SELECT MIN(languages.id) AS id, SUM(language_posts.hour) AS hour, languages.language, MIN(languages.color_code) AS color_code FROM study_hours_posts JOIN language_posts JOIN languages ON study_hours_posts.id = language_posts.study_hours_post_id AND language_posts.language_id = languages.id WHERE study_hours_posts.user_id=? AND study_hours_posts.study_date LIKE ? GROUP BY languages.language ORDER BY MIN(languages.id)');
$stmt->execute(array(
  $_SESSION['user_id'],
  "{$_GET['month']}%"
));

for($i = 0; $i < 8; $i++) {
  $pie_languages[$i]['hour'] = 0;
  $pie_languages[$i]['language'] = '';
  $pie_languages[$i]['color_code'] = '';
}

foreach($stmt->fetchAll() as $index => $pie_languages_data) {
  $pie_languages[$pie_languages_data['id'] - 1]['hour'] = round((double)$pie_languages_data['hour'], 2);
  $pie_languages[$pie_languages_data['id'] - 1]['language'] = $pie_languages_data['language'];
  $pie_languages[$pie_languages_data['id'] - 1]['color_code'] = $pie_languages_data['color_code'];
}

echo json_encode($pie_languages);