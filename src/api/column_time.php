<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=utf-8');

$array = array();
$stmt = $db->prepare('SELECT sum(total_hour) FROM study_hours_posts WHERE user_id=? AND study_date LIKE ?');
$stmt->execute(array(
  $_SESSION['user_id'],
  "{$_GET['month']}%"
));

$array['month_hour'] = round($stmt->fetchAll()[0][0]);
$array['column_time'] = [
  'cols' => [
    [
      'id' => '',
      'label' => 'Date',
      'pattern' => '',
      'type' => 'string'
    ],
    [
      'id' => '',
      'label' => 'Hour',
      'pattern' => '',
      'type' => 'number'
    ],
  ],
  'rows' => [
    [
      'c' => [
        ['v' => '1', 'f' => null],
        ['v' => 4, 'f' => null]
      ],
      'c' => [
        ['v' => '3', 'f' => null],
        ['v' => 2, 'f' => null]
      ],
      'c' => [
        ['v' => '5', 'f' => null],
        ['v' => 0, 'f' => null]
      ],
      'c' => [
        ['v' => '7', 'f' => null],
        ['v' => 8, 'f' => null]
      ],
      'c' => [
        ['v' => '9', 'f' => null],
        ['v' => 3, 'f' => null]
      ],
      'c' => [
        ['v' => '11', 'f' => null],
        ['v' => 5, 'f' => null]
      ],
    ]
  ]
];

$a = '{"cols":[{"id":"","label":"年","pattern":"","type":"string"},{"id":"","label":"売上","pattern":"","type":"number"}],"rows":[{"c":[{"v":"2000","f":null},{"v":500,"f":null}]},{"c":[{"v":"2001","f":null},{"v":1000,"f":null}]},{"c":[{"v":"2002","f":null},{"v":1500,"f":null}]},{"c":[{"v":"2003","f":null},{"v":2000,"f":null}]}]}';

$array['json'] = json_decode($a, true);
echo json_encode($array);