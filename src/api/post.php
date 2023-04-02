<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

if (!empty($_POST)) {
  $date = $_POST['date'];
  $content_id = $_POST['content'];
  $language_id = $_POST['language'];
  $study_hour = $_POST['study_hour'];

  $stmt = $db->prepare('INSERT INTO study_hours_posts SET user_id=?, total_hour=?, study_date=?');
  $stmt->execute(array(
    $_SESSION['user_id'],
    $study_hour,
    $date
  ));

  $last_id = $db->lastInsertId();

  $stmt = $db->prepare('INSERT INTO content_posts SET study_hours_post_id=?, content_id=?, hour=?');
  $stmt->execute(array(
    $last_id,
    $content_id,
    $study_hour
  ));

  $stmt = $db->prepare('INSERT INTO language_posts SET study_hours_post_id=?, language_id=?, hour=?');
  $stmt->execute(array(
    $last_id,
    $language_id,
    $study_hour
  ));
}
