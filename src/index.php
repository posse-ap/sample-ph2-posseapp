<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
  $_SESSION['time'] = time();

  $stmt = $db->query('SELECT language, color_code FROM languages');
  $languages = $stmt->fetchAll();
  $stmt = $db->query('SELECT content, color_code FROM contents');
  $contents = $stmt->fetchAll();
} else {
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/auth/login.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>POSSE</title>
</head>

<body>
  <header class="header">
    <div class="d-lg-flex header-container mx-auto">
      <div class="d-flex">
        <img src="/img/header-logo.png" class="header-img pr-3">
        <p class="header-text my-auto"><span>1st</span> week</p>
      </div>
      <button class="post-btn mr-0 ml-auto my-auto d-none d-lg-block" data-toggle="modal" data-target="#modalPost">記録・投稿</button>
    </div>
  </header>

  <main>
    <div class="main-container mx-auto">
      <div class="cards d-lg-flex justify-content-between">

        <div class="left-cards">
          <div class="time-cards d-flex justify-content-between">
            <div class="card today-card text-center">
              <p class="time-cards-title mt-2 mt-lg-3 mb-0 mb-md-1">Today</p>
              <p class="font-weight-bold h1 my-1 my-lg-2" id="today_hour"></p>
              <p class="mb-2 mb-lg-3 text-muted hour">hour</p>
            </div>
            <div class="card month-card text-center">
              <p class="time-cards-title mt-2 mt-lg-3 mb-0 mb-md-1">Month</p>
              <p class="font-weight-bold h1 my-1 my-lg-2" id="month_hour"></p>
              <p class="mb-2 mb-lg-3 text-muted hour">hour</p>
            </div>
            <div class="card total-card text-center">
              <p class="time-cards-title mt-2 mt-lg-3 mb-0 mb-md-1">Total</p>
              <p class="font-weight-bold h1 my-1 my-lg-2" id="total_hour"></p>
              <p class="mb-2 mb-lg-3 text-muted hour">hour</p>
            </div>
          </div>

          <hr class="d-lg-none">

          <div class="card time-graph-card">
            <div id="pc_columnchart_values" class="d-none d-lg-block"></div>
            <div id="sp_columnchart_values" class="d-block d-lg-none"></div>
          </div>
        </div>

        <div class="right-cards d-flex justify-content-between">
          <div class="card language-card">
            <div class="language-card-container">
              <p class="font-weight-bold pt-4 lg-h5 mb-0">学習言語</p>
              <div id="language_piechart"></div>
              <div class="language-tag">
                <?php foreach ($languages as $language) : ?>
                  <p>
                    <span class="circle" style="background-color: <?php echo $language['color_code']; ?>"></span>
                    <span class="text-nowrap mr-2"><?php echo $language['language']; ?></span>
                  </p>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="card contents-card">
            <div class="contents-card-container">
              <p class="font-weight-bold pt-4 lg-h5 mb-0">学習コンテンツ</p>
              <div id="contents_piechart"></div>
              <div class="contents-tag">
                <?php foreach ($contents as $content) : ?>
                  <p>
                    <span class="circle" style="background-color: <?php echo $content['color_code']; ?>"></span>
                    <span class="text-nowrap mr-2"><?php echo $content['content']; ?></span>
                  </p>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="mt-3 mt-lg-4 main-container mx-auto">
    <p class="text-center font-weight-bold"><span class="pr-3" id="prev">&lt;</span><span id="thisMonth"></span><span class="pl-3" id="next">&gt;</span></p>

    <button class="post-btn mx-auto d-block d-lg-none" data-toggle="modal" data-target="#modalPost">記録・投稿</button>
  </footer>

  <!-- modal main -->
  <div class="modal fade" id="modalPost" tab-index="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-container">
          <form>
            <div class="form-group d-lg-flex justify-content-between">
              <div class="modal-left-parts">
                <div class="modal-date-part">
                  <p class="font-weight-bold modal-title">学習日</p>
                  <input type="text" id="studyDate" data-toggle="modal" data-target="#modalCalendar" name="date" readonly>
                </div>
                <div class="modal-contents-pc-part d-none d-lg-block pt-3">
                  <p class="font-weight-bold modal-title">学習コンテンツ</p>
                  <label for="contents2" class="form-check form-check-inline">
                    <input id="contents2" type="radio" value="1" name="content" class="form-check-input">
                    ドットインストール
                  </label>
                  <label for="contents1" class="form-check form-check-inline">
                    <input id="contents1" type="radio" value="2" name="content" class="form-check-input">
                    N予備校
                  </label>
                  <label for="contents3" class="form-check form-check-inline">
                    <input id="contents3" type="radio" value="3" name="content" class="form-check-input">
                    POSSE課題
                  </label>
                </div>

                <div class="modal-contents-sp-part d-block d-lg-none pt-3">
                  <p class="font-weight-bold modal-title">学習コンテンツ</p>
                  <div class="modal-contents-select-box" id="modal-contents-select-box">
                    <select>
                      <option>N予備校</option>
                    </select>
                    <div class="modal-contents-over-select"></div>
                  </div>
                  <div id="modal-contents-check-box" class="flex-column p-2">
                    <label for="contents5" class="form-check">
                      <input type="radio" id="contents5" value="1" name="content" class="form-check-input">
                      ドットインストール
                    </label>
                    <label for="contents4" class="form-check">
                      <input type="radio" id="contents4" value="2" name="content" class="form-check-input">
                      N予備校
                    </label>
                    <label for="contents6" class="form-check">
                      <input type="radio" id="contents6" value="3" name="content" class="form-check-input">
                      POSSE課題
                    </label>
                  </div>
                </div>

                <div class="modal-language-pc-part d-none d-lg-block pt-3">
                  <p class="font-weight-bold modal-title">学習言語</p>
                  <label for="language1" class="form-check form-check-inline">
                    <input id="language1" type="radio" value="1" name="language" class="form-check-input">
                    HTML
                  </label>
                  <label for="language2" class="form-check form-check-inline">
                    <input id="language2" type="radio" value="2" name="language" class="form-check-input">
                    CSS
                  </label>
                  <label for="language3" class="form-check form-check-inline">
                    <input id="language3" type="radio" value="3" name="language" class="form-check-input">
                    JavaScript
                  </label>
                  <label for="language4" class="form-check form-check-inline">
                    <input id="language4" type="radio" value="4" name="language" class="form-check-input">
                    PHP
                  </label>
                  <label for="language5" class="form-check form-check-inline">
                    <input id="language5" type="radio" value="5" name="language" class="form-check-input">
                    Laravel
                  </label>
                  <label for="language6" class="form-check form-check-inline">
                    <input id="language6" type="radio" value="6" name="language" class="form-check-input">
                    SQL
                  </label>
                  <label for="language7" class="form-check form-check-inline">
                    <input id="language7" type="radio" value="7" name="language" class="form-check-input">
                    SHELL
                  </label>
                  <label for="language8" class="form-check form-check-inline">
                    <input id="language8" type="radio" value="8" name="language" class="form-check-input">
                    情報システム基礎知識(その他)
                  </label>
                </div>

                <div class="modal-language-sp-part d-block d-lg-none pt-3">
                  <p class="font-weight-bold modal-title">学習言語</p>
                  <div class="modal-language-select-box" id="modal-language-select-box">
                    <select>
                      <option>HTML</option>
                    </select>
                    <div class="modal-language-over-select"></div>
                  </div>
                  <div id="modal-language-check-box" class="flex-column p-2">

                    <label for="language9" class="form-check">
                      <input id="language9" type="radio" value="1" name="language" class="form-check-input">
                      HTML
                    </label>

                    <label for="language10" class="form-check">
                      <input id="language10" type="radio" value="2" name="language" class="form-check-input">
                      CSS
                    </label>


                    <label for="language11" class="form-check">
                      <input id="language11" type="radio" value="3" name="language" class="form-check-input">
                      JavaScript
                    </label>

                    <label for="language12" class="form-check">
                      <input id="language12" type="radio" value="4" name="language" class="form-check-input">
                      PHP
                    </label>


                    <label for="language13" class="form-check">
                      <input id="language13" type="radio" value="5" name="language" class="form-check-input">
                      Laravel
                    </label>


                    <label for="language14" class="form-check">
                      <input id="language14" type="radio" value="6" name="language" class="form-check-input">
                      SQL
                    </label>


                    <label for="language15" class="form-check">
                      <input id="language15" type="radio" value="7" name="language" class="form-check-input">
                      SHELL
                    </label>


                    <label for="language16" class="form-check">
                      <input id="language16" type="radio" value="8" name="language" class="form-check-input">
                      情報システム基礎知識(その他)
                    </label>
                  </div>
                </div>
              </div>
              <div class="modal-right-parts pt-3 pt-lg-0">
                <div class="modal-time-part">
                  <p class="font-weight-bold modal-title">学習時間</p>
                  <input type="text" name="study_hour">
                </div>
                <div class="modal-twitter-part pt-3">
                  <p class="font-weight-bold modal-title">Twitter用コメント</p>
                  <textarea id="postTwitter" cols="0" rows="9"></textarea>
                </div>
                <div class="modal-twitter-auto-part pt-1">
                  <input id="twitter" type="checkbox" checked><label for="twitter">Twitterに自動投稿する</label>
                </div>
              </div>
            </div>
            <button type="button" class="post-btn d-block mx-auto mt-3 mb-4" id="to-modalLoading" data-toggle="modal" data-target="#modalLoading">記録・投稿</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- modal main -->

  <!-- modal calendar -->
  <div class="modal fade" id="modalCalendar" tab-index="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&larr;</span>
        </button>
        <div class="modal-container">
          <div class="modal-calendar">
            <table>
              <thead>
                <tr>
                  <th id="calendarPrev" colspan="2">&lt;</th>
                  <th id="calendarThisMonth" colspan="3"></th>
                  <th id="calendarNext" colspan="2">&gt;</th>
                </tr>
                <tr class="calendar-day">
                  <th>Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th>Sat</th>
                </tr>
              </thead>

              <tbody id="calendar-tbody">
              </tbody>
            </table>
            <button type="button" class="post-btn d-block mx-auto mt-4" id="decideCalendar">決定</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal calendar -->

  <!-- modal loading -->
  <div class="modal fade" id="modalLoading" tab-index="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-container">
          <div class="loader-wrap">
            <div class="loader">Loading...</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal loading -->

  <!-- modal success -->
  <div class="modal fade" id="modalSuccess" tab-index="-1" aria-hidden="true">
    <div class="modal-dialog modal-success-dialog">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-container text-center">
          <p class="modal-success-color">AWESOME!</p>
          <span class="modal-success-color modal-check-mark"></span>
          <p>記録・投稿<br>完了しました</p>
        </div>
      </div>
    </div>
  </div>
  <!-- modal success -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="js/main.js"></script>
  <script src="js/calender.js"></script>
</body>

</html>