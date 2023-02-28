let date = new Date();
date.setDate(date.getDate() + 1);
  
$('#date').datepicker({
  minDate: date,
});

$('#time').timepicker({
  'minTime': '11:00',
	'maxTime': '21:00',
});

function reserve_cancel(res_id) {
  let flg = window.confirm("予約をキャンセルしますか？");
  if (flg) {
    location.href = '/res_del?id='+ res_id
  }
}

function res_form_set(res_id, valDate, valTime, valNum) {
  $('#date').val(valDate);
  $('#time').val(valTime);
  $('#member').val(valNum);
  $('#id').val(res_id);
  $('.modal-reserve').css('display', '');
  $('.modal-review').css('display', 'none');
  $('.modal-qr').css('display', 'none');
  $('.modal-container').addClass('active');
	return false;
}

function modal_close() {
  $('.modal-container').removeClass('active');
}

function reserve_change() {
  let flg = window.confirm("予約を変更します");
  if (flg) {
    let date = $('#date').val();
    let time = $('#time').val();
    $('#reserve').val(date + ' ' + time);
    $('#fm_reserve').submit();
  }
}

function review_open(shop_id, user_id) {
  let data = shop_list.filter(function(item, index){
    if (item.id == shop_id) return true;
  });

  $('.modal-reserve').css('display', 'none');
  $('.modal-review').css('display', '');
  $('.modal-qr').css('display', 'none');
  $('.modal-container').addClass('active');
  $('.modal-shop_name').text('～ ' + data[0].name + 'の評価 ～');
  
  if (data[0].point != undefined) {
    $('#star' + data[0].point).click();
  } else {
    $('#star1').click();
  }

  if (data[0].rev_id != undefined) {
    $('#rev_id').val(data[0].rev_id);
  } else {
    $('#rev_id').val('');
  }

  if (data[0].comment != undefined) {
    $('#comment').val(data[0].comment);
  } else {
    $('#comment').val('');
  }

  $('#rev_shop_id').val(shop_id);
  $('#rev_user_id').val(user_id);
}

function review() {
  let flg = window.confirm("評価を登録します");
  if (flg) {
    let comment = $('#comment').val().length;
    if (comment > 200) {
      alert('コメントは200文字以内でお願い致します');
      return;
    }

    $('#fm_review').submit();
  }
}

function qr(res_id) {
  let data = reserve_list.filter(function(item, index){
    if (item.id == res_id) return true;
  });
  
  $('.qr_tbl').find('td').eq(0).text(data[0].name);
  $('.qr_tbl').find('td').eq(1).text(data[0].date);
  $('.qr_tbl').find('td').eq(2).text(data[0].time);
  $('.qr_tbl').find('td').eq(3).text(data[0].member + '名');
  
  let url = $('.qr_url').text();

  $('.modal-reserve').css('display', 'none');
  $('.modal-review').css('display', 'none');
  $('.modal-qr').css('display', '');
  $('.modal-container').addClass('active');
  var qrtext = url + '/owner/qr?res_id=' + data[0].id +'&path=owner.qr';

  var utf8qrtext = unescape(encodeURIComponent(qrtext));
  $("#img-qr").html("");
  $("#img-qr").qrcode({ text: utf8qrtext });
  $('.url').text(qrtext);
}