'use strict'
{
  const today = new Date();
  let year = today.getFullYear();
  let month = today.getMonth();
  let url = new URL(location);
  let queryDate;

  // 指定された月のデータを表示する
  function displayThisMonth() {
    const footerMonth = `${year}年 ${String(month + 1).padStart(2, '0')}月`;
    document.getElementById('thisMonth').textContent = footerMonth;

    const displayMonth = `${year}-${String(month + 1).padStart(2, '0')}`;

    $.ajax({
      url: 'api/month_hour.php',
      type: 'GET',
      dataType: 'json',
      data: {
        month: displayMonth
      }
    })
    .done(function (data) {
      $('#month_hour').text(data);
    })
  }

  // 固定の時間(TodayとTodal)を表示する
  function displayFixedHours() {
    $.ajax({
      url: 'api/fixed_hours.php',
      type: 'GET',
      dataType: 'json',
    })
    .done(function (data) {
      $('#today_hour').text(data['today_hour']);
      $('#total_hour').text(data['total_hour']);
    })
    .fail(function (XMLHttpRequest, textStatus, errorThrown) {
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    })
  }

  displayThisMonth();
  displayFixedHours();
  
  document.getElementById('prev').addEventListener('click', ()=>{
    month--;
    if(month < 0){
      year--;
      month = 11;
    }
    displayThisMonth();
    google.charts.setOnLoadCallback(drawChart);
  });
  
  document.getElementById('next').addEventListener('click', ()=>{
    month++;
    if(month > 11){
      year++;
      month = 0;
    }
    displayThisMonth();
    google.charts.setOnLoadCallback(drawChart);
  });


  document.getElementById('decideCalendar').addEventListener('click', ()=>{
    $('#modalCalendar').modal('hide');
  })


  let contentsExpanded = false;
  const contentsSelectBox = document.getElementById('modal-contents-select-box');
  const contentsCheckbox = document.getElementById('modal-contents-check-box');
  
  contentsSelectBox.addEventListener('click', ()=>{
    if(!contentsExpanded){
      contentsCheckbox.style.display = "block";
      contentsExpanded = true;
    }else{
      contentsCheckbox.style.display = "none";
      contentsExpanded = false;
    }
  });

  let languageExpanded = false;
  const languageSelectBox = document.getElementById('modal-language-select-box');
  const languageCheckbox = document.getElementById('modal-language-check-box');

  languageSelectBox.addEventListener('click', ()=>{
    if(!languageExpanded){
      languageCheckbox.style.display = "block";
      languageExpanded = true;
    }else{
      languageCheckbox.style.display = "none";
      languageExpanded = false;
    }
  });

  // modalSuccessが表示されたタイミングでmodalLoadingを非表示にする
  $("#modalSuccess").on('shown.bs.modal', function () {
    $('#modalLoading').modal('hide')
    $('#modalPost').modal('hide')
  });
  
  // 投稿処理
  $('#to-modalLoading').on('click', function (e) {
    e.preventDefault();
    $('#modalPost').modal('hide');
    $('#modalLoading').modal('show');

    let date = $('input[name="date"]').val().slice(0, -1).replace('年', '-').replace('月', '-');
    let contents = [];
    let languages = [];
    let study_hour = $('input[name="study_hour"]').val();

    $('input[name="contents[]"]:checked').each(function () {
      contents.push($(this).val());
    });
    $('input[name="languages[]"]:checked').each(function () {
      languages.push($(this).val());
    });

    $.ajax({
      url: 'api/post.php',
      type: 'POST',
      dataType: 'text',
      data: {
        date: date,
        contents: contents,
        languages: languages,
        study_hour: study_hour
      }
    })
    .done(function (data) {
      $('#modalSuccess').modal('show');
      $('form')[0].reset();

      const today = new Date();

      $('#studyDate').val(`${today.getFullYear()}年${String(today.getMonth() + 1).padStart(2, '0')}月${String(today.getDate()).padStart(2, '0')}日`);
    
      displayFixedHours();
      displayThisMonth();
    })
    .fail(function (XMLHttpRequest, textStatus, errorThrown) {
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    })
  });


  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);
  
  function drawChart() {
    const displayMonth = `${year}-${String(month + 1).padStart(2, '0')}`;

    // 学習時間
    const lastDay = new Date(year, month + 1, 0).getDate();
    var columnChartData = [];

    for (let i = 0; i < lastDay; i++) {
      columnChartData[i] = {
        date: i % 2 == 0 ? '' : String(i + 1),
        hour: 0,
        color: ''
      }
    }

    $.ajax({
      url: 'api/column_time.php',
      type: 'GET',
      dataType: 'json',
      data: {
        month: displayMonth
      },
      async: false
    })
    .done(function (data) {
      if (data) {
        data.forEach(element => {
          columnChartData[parseInt(element['date']) - 1]['hour'] = parseInt(element['hour'])
          columnChartData[parseInt(element['date']) - 1]['color'] = element['color']
        })
      }
    });

    var data = google.visualization.arrayToDataTable([
      ["Date", "Hour", { role: "style" }],
      [columnChartData[0]['date'], columnChartData[0]['hour'], columnChartData[0]['color']],
      [columnChartData[1]['date'], columnChartData[1]['hour'], columnChartData[1]['color']],
      [columnChartData[2]['date'], columnChartData[2]['hour'], columnChartData[2]['color']],
      [columnChartData[3]['date'], columnChartData[3]['hour'], columnChartData[3]['color']],
      [columnChartData[4]['date'], columnChartData[4]['hour'], columnChartData[4]['color']],
      [columnChartData[5]['date'], columnChartData[5]['hour'], columnChartData[5]['color']],
      [columnChartData[6]['date'], columnChartData[6]['hour'], columnChartData[6]['color']],
      [columnChartData[7]['date'], columnChartData[7]['hour'], columnChartData[7]['color']],
      [columnChartData[8]['date'], columnChartData[8]['hour'], columnChartData[8]['color']],
      [columnChartData[9]['date'], columnChartData[9]['hour'], columnChartData[9]['color']],
      [columnChartData[10]['date'], columnChartData[10]['hour'], columnChartData[10]['color']],
      [columnChartData[11]['date'], columnChartData[11]['hour'], columnChartData[11]['color']],
      [columnChartData[12]['date'], columnChartData[12]['hour'], columnChartData[12]['color']],
      [columnChartData[13]['date'], columnChartData[13]['hour'], columnChartData[13]['color']],
      [columnChartData[14]['date'], columnChartData[14]['hour'], columnChartData[14]['color']],
      [columnChartData[15]['date'], columnChartData[15]['hour'], columnChartData[15]['color']],
      [columnChartData[16]['date'], columnChartData[16]['hour'], columnChartData[16]['color']],
      [columnChartData[17]['date'], columnChartData[17]['hour'], columnChartData[17]['color']],
      [columnChartData[18]['date'], columnChartData[18]['hour'], columnChartData[18]['color']],
      [columnChartData[19]['date'], columnChartData[19]['hour'], columnChartData[19]['color']],
      [columnChartData[20]['date'], columnChartData[20]['hour'], columnChartData[20]['color']],
      [columnChartData[21]['date'], columnChartData[21]['hour'], columnChartData[21]['color']],
      [columnChartData[22]['date'], columnChartData[22]['hour'], columnChartData[22]['color']],
      [columnChartData[23]['date'], columnChartData[23]['hour'], columnChartData[23]['color']],
      [columnChartData[24]['date'], columnChartData[24]['hour'], columnChartData[24]['color']],
      [columnChartData[25]['date'], columnChartData[25]['hour'], columnChartData[25]['color']],
      [columnChartData[26]['date'], columnChartData[26]['hour'], columnChartData[26]['color']],
      [columnChartData[27]['date'], columnChartData[27]['hour'], columnChartData[27]['color']],
      [columnChartData[28]['date'], columnChartData[28]['hour'], columnChartData[28]['color']],
      [columnChartData[29]['date'], columnChartData[29]['hour'], columnChartData[29]['color']],
    ]);
    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
      {
        calc: "stringify",
        type: "string",
        role: "annotation"
      },
      2
    ]);
    var pc_options = {
      width: '100%',
      height: '400',
      bar: {
        groupWidth: "50%"
      },
      legend: {
        position: "none"
      },
      vAxis: {
        format: '0h',
        gridlines: {
          color: '#ffffff'
        }
      }
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("pc_columnchart_values"));
    chart.draw(view, pc_options);
  
    var sp_options = {
      width: '100%',
      height: '200',
      bar: {
        groupWidth: "50%"
      },
      legend: {
        position: "none"
      },
      vAxis: {
        format: '0h',
        gridlines: {
          color: '#ffffff'
        }
      }
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("sp_columnchart_values"));
    chart.draw(view, sp_options);
  
    // 学習言語
    var pieLangagesData = [];

    $.ajax({
      url: 'api/pie_languages.php',
      type: 'GET',
      dataType: 'json',
      data: {
        month: displayMonth
      },
      async: false
    })
    .done(function (data) {
      if (data) {
        pieLangagesData = data;
      }
    })

    var data = google.visualization.arrayToDataTable([
      ['Language', 'Hour'],
      [pieLangagesData[0]['language'], pieLangagesData[0]['hour']],
      [pieLangagesData[1]['language'], pieLangagesData[1]['hour']],
      [pieLangagesData[2]['language'], pieLangagesData[2]['hour']],
      [pieLangagesData[3]['language'], pieLangagesData[3]['hour']],
      [pieLangagesData[4]['language'], pieLangagesData[4]['hour']],
      [pieLangagesData[5]['language'], pieLangagesData[5]['hour']],
      [pieLangagesData[6]['language'], pieLangagesData[6]['hour']],
      [pieLangagesData[7]['language'], pieLangagesData[7]['hour']],
    ]);
  
    var options = {
      legend: {
        position: "none",
      },
      pieHole: 0.5,
      slices: {
        0: {
          color: '#59DEEB'
        },
        1: {
          color: '#49BCF2'
        },
        2: {
          color: '#4D8DDB'
        },
        3: {
          color: '#496EF2'
        },
        4: {
          color: '#4F4DEB'
        },
        5: {
          color: '#633BD4'
        },
        6: {
          color: '#A34DF8'
        },
        7: {
          color: '#B63AE0'
        }
      },
      chartArea: {
        width: '100%',
        height: '100%'
      }
    };
  
    var chart = new google.visualization.PieChart(document.getElementById('language_piechart'));
    chart.draw(data, options);
  
    // 学習コンテンツ
    var pieContentsData = [];

    $.ajax({
      url: 'api/pie_contents.php',
      type: 'GET',
      dataType: 'json',
      data: {
        month: displayMonth
      },
      async: false
    })
    .done(function (data) {
      if (data) {
        pieContentsData = data;
      }
    })

    var data = google.visualization.arrayToDataTable([
      ['Content', 'Hour'],
      [pieContentsData[0]['content'], pieContentsData[0]['hour']],
      [pieContentsData[1]['content'], pieContentsData[1]['hour']],
      [pieContentsData[2]['content'], pieContentsData[2]['hour']],
    ]);
  
    var options = {
      legend: {
        position: "none",
      },
      pieHole: 0.5,
      slices: {
        0: {
          color: '#A3E0FF'
        },
        1: {
          color: '#72CDFA'
        },
        2: {
          color: '#3184AD'
        },
      },
      chartArea: {
        width: '100%',
        height: '100%',
      }
    };
  
    var chart = new google.visualization.PieChart(document.getElementById('contents_piechart'));
    chart.draw(data, options);
  }
}

