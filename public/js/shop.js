function search() {
  $('#search').submit();
}

function link_search(valId, valKbn) {
  $('#sel_' + valKbn).val(valId);
  search();
}

function like(event, shop_id, user_id) {
  let url = event.target.src.split('/');
  let file_name = url[url.length - 1];
  let process;
  if (file_name == 'heart_red.png') {
    event.target.setAttribute('src', 'img/heart_gray.png');
    process = 'del';
  } else {
    event.target.setAttribute('src', 'img/heart_red.png');
    process = 'insert';
  }
  $.ajax({
    url: '/like',
    method: 'get',
    data: {
      'shop_id': shop_id,
      'user_id': user_id,
      'process': process
    },
  });

  //location.href = '/like?shop_id='+ shop_id +'&user_id='+ user_id +'&process='+ process;
}

function res_change(res_id) {
  $('.modal-container').addClass('active');
	return false;
}

function modal_close() {
  $('.modal-container').removeClass('active');
}