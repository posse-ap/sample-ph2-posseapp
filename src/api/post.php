<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

if(!empty($_POST)) {
  $date = $_POST['date'];
  $contents = $_POST['contents'];
  $languages = $_POST['languages'];
  $study_hour = $_POST['study_hour'];
  
  $stmt = $db->prepare('INSERT INTO study_hours_posts SET user_id=?, total_hour=?, study_date=?');
  $stmt->execute(array(
    $_SESSION['user_id'],
    $study_hour,
    $date
  ));
  
  $last_id = $db->lastInsertId();

  $hour_per_content = $study_hour / count($contents);
  $hour_per_language = $study_hour / count($languages);

  foreach($contents as $content_id) {
    $stmt = $db->prepare('INSERT INTO content_posts SET study_hours_post_id=?, content_id=?, hour=?');
    $stmt->execute(array(
      $last_id,
      $content_id,
      $hour_per_content
    ));
  }

  foreach($languages as $language_id) {
    $stmt = $db->prepare('INSERT INTO language_posts SET study_hours_post_id=?, language_id=?, hour=?');
    $stmt->execute(array(
      $last_id,
      $language_id,
      $hour_per_language
    ));
  }
}