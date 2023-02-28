let date = new Date();
date.setDate(date.getDate() + 1);
$('.search_date').datepicker();


$(function () {
  $("#tx_overview").keyup(function(){
    var txtcount = $(this).val().length;
    $("#txtlmt").text(txtcount);
    if(txtcount == 0){
      $("#txtlmt").text("0");
    } 
    if(txtcount >=200){
      $("#txtlmt").css("color","red");
    } else {
      $("#txtlmt").css("color","#000");
    }
  });
});

$('.register_btn').click(function () {
  let flg = window.confirm('お店を登録します');
  if (flg) {
    $('#fm_owner_reg').submit();
  }
});

function menu(menu, shop_id) {
  window.location.href = "/owner?menu="+menu+'&shop_id='+shop_id;
}

function search(shop_id) {
  let name = $('#tx_search').val();
  let date1 = $('#search_date1').val();
  let date2 = $('#search_date2').val();
  let check = $('#search_check').val();
  let situation = $('#search_situation').val();

  window.location.href = '/owner?menu=0&shop_id=' + shop_id + '&name=' + name + '&date1=' + date1 + '&date2=' + date2 + '&check=' + check + '&situation=' + situation;
}

$(function () {
  $(".search_date").keyup(function(){
    $(this).val('');
  });
});

function check(res_id, val) {
  let check = $(val).prop("checked");
  let chk=0;
  if (check == true) {
    chk = 1;
  }
  $.ajax({
    type: 'GET',
    url: '/owner/check',
    data: {
      id: res_id,
      check: chk,
    },
  }).then (function(result) {
  }).catch(function(request, status, error) {
  });
}

function situation(res_id, val) {
  let flg = window.confirm('予約状況を変更します');
  if (flg) {
    let situation = $(val).val();
    $.ajax({
      type: 'GET',
      url: '/owner/situation',
      data: {
        id: res_id,
        situation: situation,
      },
    }).then (function(result) {
    }).catch(function(request, status, error) {
    });
  }
}

function owner_reserve(res_id) {
  let flg = window.confirm('ご予約を来店済みに変更します');
  if (flg) {
  $.ajax({
    type: 'GET',
    url: '/owner/owner_reserve',
    data: {
      id: res_id,
    },
  }).then(function (result) {
    $('.qr_btn_td').html('<p>ご来店登録しました</p>')
  }).catch(function(request, status, error) {
  });
  }
}