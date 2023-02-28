$('.shop_register').click(function () {
  $('#admin_reg_layout').css('display', '');
  $('#admin_reg_complete_layout').css('display', 'none');
  $('.display_message').css('display', '');

  $('.admin_ttl').text('【 新規登録 】');
  $('#id').val('');
  $('#name').val('');
  $('#area').val('');
  $('genre').val('');
  $('#tx_overview').val('');
  $('#manager').val('');
  $('#email').val('');
  $('#post_code').val('');
  $('#address').val('');
  $('#tel').val('');
  $('#display').val('0');

  $('#span_id').text('');
  $('#span_pw').text('');
  $('.shop_data').css('display', 'none');

  $('.error').text('');

  $('.modal-reserve').css('display', '');
  $('.modal-review').css('displ0ay', 'none');
  $('.modal-container').addClass('active');  
  return false;
});

function modal_close() {
  $('.modal-container').removeClass('active');
}

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
  let valID = $('#id').val();
  let str1 = '';
  if (valID == '' || valID == undefined) {
    str1 = '新規登録します';
  } else {
    str1 = '店舗データを修正します';
  }

  let flg = window.confirm(str1);
  if (flg) {
    $('#fm_admin_reg').submit();
  }
});

function shop_set(valID, kbn) {
  let data = shop_list.filter(function(item, index){
    if (item.id == valID) return true;
  });

  switch (kbn) {
    case 'set':
      $('#id').val(data[0].id);
      $('#name').val(data[0].name);
      $('#area').val(data[0].area_id);
      $('#genre').val(data[0].genre_id);
      $('#tx_overview').val(data[0].overview);
      $('#manager').val(data[0].manager);
      $('#email').val(data[0].email);
      $('#post_code').val(data[0].post_code);
      $('#address').val(data[0].address);
      $('#tel').val(data[0].tel);
      $('#display').val(data[0].display);

      $('#span_id').text(data[0].id);
      $('#span_pw').text(data[0].password);
      $('.shop_data').css('display', '');

      $('.error').text('');

      $('.admin_ttl').text('【 店舗参照・修正 】');
      $('.modal-reserve').css('display', '');
      $('.modal-review').css('display', 'none');
      $('.modal-container').addClass('active');  

      $('#admin_reg_layout').css('display', '');
      $('#admin_reg_complete_layout').css('display', 'none');
      $('.display_message').css('display', 'none');
      return false;
      break;
    case 'mail':
      $('.modal-container').addClass('active');

      $('#mail_user').val(data[0].email);
      $('#admin_reg_layout').css('display', 'none');
      $('#admin_reg_complete_layout').css('display', '');
      $('.display_message').css('display', 'none');
      break;
  }
}

function search() {
  $('#search').submit();
}

$('.mail_btn').click(function () {
  let flg = window.confirm('メールを送信します');
  if (flg) {
    $('#fm_mail').submit();
  }
});