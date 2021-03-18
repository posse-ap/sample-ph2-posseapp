'use strict'
{
  const today = new Date();
  let year = today.getFullYear();
  let month = today.getMonth();
  let url = new URL(location);
  let queryDate;

  function displayThisMonth() {
    const footerMonth = `${year}年 ${String(month + 1).padStart(2, '0')}月`;
    document.getElementById('thisMonth').textContent = footerMonth;

    const displayMonth = `${year}-${String(month + 1).padStart(2, '0')}`;

    $.ajax({
      url: 'api/column_time.php',
      type: 'GET',
      dataType: 'json',
      data: {
        month: displayMonth
      }
    })
    .done(function (data) {
      $('#month_hour').text(data['month_hour']);
      console.log(data);
    })
  }

  function displayFixedHours() {
    $.ajax({
      url: 'api/fixed_hours.php',
      type: 'GET',
      dataType: 'json',
    })
    .done(function (data) {
      console.log(data)
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
  });
  
  document.getElementById('next').addEventListener('click', ()=>{
    month++;
    if(month > 11){
      year++;
      month = 0;
    }
    displayThisMonth();
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
      console.log(data);
      $('#modalLoading').modal('hide');
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
}

