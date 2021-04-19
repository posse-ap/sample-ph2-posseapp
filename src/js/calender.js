'use strict'
{
  const calendarToday = new Date();
  let calendarYear = calendarToday.getFullYear();
  let calendarMonth = calendarToday.getMonth();
  let calendarDate = calendarToday.getDate();
  
  document.getElementById('studyDate').value = `${calendarYear}年${String(calendarMonth + 1).padStart(2, '0')}月${String(calendarDate).padStart(2, '0')}日`;

  function getPrevMonth(){
    const dates = [];
    const prevLastDate = new Date(calendarYear, calendarMonth, 0).getDate();
    const prevDays = new Date(calendarYear, calendarMonth, 1).getDay();

    for(let i = 0; i < prevDays; i++){
      dates.unshift({
        date: prevLastDate - i,
        today: false,
        disabled: true,
        pastDays: false,
      });
    }

    return dates
  }
  
  function getThisMonth(){
    const dates = [];
    const lastDate = new Date(calendarYear, calendarMonth + 1, 0).getDate();

    for(let i = 1; i <= lastDate; i++){
      if(i < calendarToday.getDate()){
        dates.push({
          date: i,
          today: false,
          disabled: false,
          pastDays: true,
        });
      }else{
        dates.push({
          date: i,
          today: false,
          disabled: false,
          pastDays: false,
        });
      }

      if(calendarYear === calendarToday.getFullYear() && calendarMonth < calendarToday.getMonth()){
        dates[i - 1].pastDays = true;
      }
      if(calendarYear < calendarToday.getFullYear()){
        dates[i - 1].pastDays = true;
      }

      if(calendarYear === calendarToday.getFullYear() && calendarMonth > calendarToday.getMonth()){
        dates[i - 1].pastDays = false;
      }
      if(calendarYear > calendarToday.getFullYear()){
        dates[i - 1].pastDays = false;
      }
    }

    if(calendarYear === calendarToday.getFullYear() && calendarMonth === calendarToday.getMonth()){
      dates[calendarToday.getDate() - 1].today = true;
      dates[calendarToday.getDate() - 1].pastDays = true;
    }

    return dates;
  }

  function getNextMonth(){
    const dates = [];
    const nextDays = new Date(calendarYear, calendarMonth + 1, 0).getDay();

    for(let i = 1; i < 7 - nextDays; i++){
      dates.push({
        date: i,
        today: false,
        disabled: true,
        pastDays: false,
      });
    }

    return dates;
  }

  function createCalendar(){
    const tbody = document.getElementById("calendar-tbody");

    while(tbody.firstChild){
      tbody.removeChild(tbody.firstChild);
    }

    const calendarThisMonth = `${calendarYear}年${String(calendarMonth + 1).padStart(2, '0')}月`;
    document.getElementById('calendarThisMonth').textContent = calendarThisMonth;

    const dates = [
      ...getPrevMonth(),
      ...getThisMonth(),
      ...getNextMonth(),
    ];
    const weeks = [];
    const weeksCount = dates.length / 7;

    for(let i = 0; i < weeksCount; i++){
      weeks.push(dates.splice(0, 7));
    }

    weeks.forEach(week => {
      const tr = document.createElement('tr');
      week.forEach(date => {
        const td = document.createElement('td');

        td.textContent = date.date;

        if(date.today){
          td.classList.add('today');
        }
        if(date.disabled){
          td.classList.add('disabled');
        }
        if(date.pastDays){
          td.classList.add('past-days');
        }

        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });

    choosePastDays();
  }

  document.getElementById('calendarPrev').addEventListener('click', ()=>{
    calendarMonth--;
    if(calendarMonth < 0){
      calendarYear--;
      calendarMonth = 11;
    }

    createCalendar();
  });

  document.getElementById('calendarNext').addEventListener('click', ()=>{
    calendarMonth++;
    if(calendarMonth > 11){
      calendarYear++;
      calendarMonth = 0;
    }

    createCalendar();
  });

  createCalendar();

  document.getElementById('decideCalendar').addEventListener('click', () => {
    if (document.getElementById('calendarClicked')) {
      document.getElementById('studyDate').value = `${calendarYear}年${String(calendarMonth + 1).padStart(2, '0')}月${String(document.getElementById('calendarClicked').innerText).padStart(2, '0')}日`
    }
    $('#modalCalendar').modal('hide');
  });

  function choosePastDays() {
    const pastDays = document.querySelectorAll('.past-days');
    pastDays.forEach(pastDay => {
      pastDay.addEventListener('click', () => {
        if (document.getElementById('calendarClicked')) {
          document.getElementById('calendarClicked').removeAttribute('id');
        }
        pastDay.setAttribute('id', 'calendarClicked');
      })
    })
  }

}
