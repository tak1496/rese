let date = new Date();
date.setDate(date.getDate() + 1);
$('#date').datepicker({
  minDate: date,
});

$('#time').timepicker({
  'minTime': '11:00',
	'maxTime': '21:00',
});

function back(path) {
  location.href = path;
}

function reserve_data_set(valId) {
  $('#' + valId).css({
    'color': '#000',
    'background-color': 'rgb(240, 221, 185)',
    'border': 'none'
  });

  let date = $('#date').val();
  let time = $('#time').val();
  let member = '';
  if ($('#member').val() != null) {
    member = $('#member').val()+'名';    
  }

  $('#tx_date').text(date);
  $('#tx_time').text(time);
  $('#tx_member').text(member); 

  $('#reserve').val(date + ' ' + time);
}

function input_erase() {
  $('#time').val('');
}

function reserve_chk() {
  if ($('#date').val() == '') {
    alert('予約日を選択してください');
    return;
  }

  if ($('#time').val() == '') {
    alert('時間を選択してください');
    return;
  }

  let date = $('#date').val();
  let time = $('#time').val();
  let member = $('#member').val()+'名';
  $('#md_date').text(date);
  $('#md_time').text(time);
  $('#md_member').text(member);

  $('.modal-container').addClass('active');
	return false;
}

function modal_close() {
  $('.modal-container').removeClass('active');
}

function reserve() {
  $('#fm_reserve').submit();
}

$(function () {
  $(".review_display").click(function () {
    if ($('.review_list').css('display') === 'block') {
      $(".review_display").text('レビューを表示');
    } else {
      $(".review_display").text('レビューを非表示');
    }

    $('.review_list').toggle();
  });
});

function reserve_login(val, shop_id) {
  switch (val) {
    case 'login':
      $('#fm_reserve').submit();
      break;
    case 'register':
      let reserve = $('#reserve').val();
      let date = $('#date').val();
      let time = $('#time').val();
      let member = $('#member').val();
      location.href = '/auth/register?path=detail&shop_id='+ shop_id +'&reserve='+ reserve +'&date='+ date +'&time='+ time +'&member='+ member;
      break;
  }
}