const target = document.getElementById("menu");
target.addEventListener("click", () => {
  const target = document.getElementById("menu");
  target.classList.toggle("open");
  const nav = document.getElementById("drawer-nav");
  nav.classList.toggle("in");
});


document.getElementById("logout").addEventListener("click", () => {
  const flg = window.confirm("ログアウトしますか？");
  if (flg) {
    location.href = '/auth/logout';
  }
});

const target2 = document.getElementById("search_img_btn");
target2.addEventListener("click", () => {
  const pos = $('#search_img_btn').offset();
  let scrX = $(window).scrollTop();
  let intT = pos.top + 30 - scrX;
  let intL = pos.left - 230;

  document.getElementById('search_menu').style.top = intT + 'px';
  document.getElementById('search_menu').style.left = intL + 'px';

  const chk = $('#search_menu').css('display');
  if (chk == 'none') {
    $('#search_menu').css('display', 'block');
  } else {
    $('#search_menu').css('display', 'none');
  }
});