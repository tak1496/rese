@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/mypage.css">
<link rel="stylesheet" href="/css/menu.css">

<link rel="stylesheet" href="/css/modal.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
<link rel="stylesheet" href="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.css">

<header class="header">
  <div class="rese_content">
    <img src="/img/rese.png" class="rese_img">
    <h1>Rese</h1>
  </div>

  <nav>
    <div class="menu" id="menu">
      <span class="menu-line-top"></span>
      <span class="menu-line-middle"></span>
      <span class="menu-line-bottom"></span>
    </div>
  </nav>

  <nav class="drawer-nav" id="drawer-nav">
    <ul class="drawer-nav-list">
      <li class="drawer-nav-item">
        <a href="/">ホーム</a>
      </li>
      @auth
      <li class="drawer-nav-item">
        <a href="#" id="logout">ログアウト</a>
      </li>
      <li class="drawer-nav-item">
        <a href="/mypage">マイページ</a>
      </li>
      @if($user->auth_id === 0)
      <li class="drawer-nav-item">
        <a href="/admin">管理ページ</a>
      </li>
      @endif
      @if($user->auth_id === 1)
      <li class="drawer-nav-item">
        <a href="/owner?shop_id={{ $user->shop_id }}&menu=0">お店管理ページ</a>
      </li>
      @endif
      @endauth
      @guest
      <li class="drawer-nav-item">
        <a href="/auth/register?path=thanks">新規登録</a>
      </li>
      <li class="drawer-nav-item">
        <a href="/auth/login?path=form.index">ログイン</a>
      </li>
      @endguest
    </ul>
  </nav>
</header>

<main>
  <div class="mypage_name">
    <h2>{{ $user->name }}さんのマイページ</h2>
  </div>
  <section class="section">
    <div class="res_layout">
      <div class="res_layout1">
        <h2 class="res_ttl">予約状況</h2>
        <div class="res_card_layout">
          @foreach($reserves as $reserve)
          <div class="res_card">
            <table class="res_card_tbl">
              <tr class="res_card_ttl">
                <td colspan="2">
                  <img src="./img/watch.png" class="image" />
                  <span>予約{{ $loop->index+1 }}</span>
                </td>
                <td onclick="reserve_cancel({{ $reserve->id }});">
                  <img src="./img/close.png" class="image close" />
                </td>
              </tr>
              <tr>
                <td>店名</td>
                <td>{{ $reserve->shop->name }}</td>
                <td></td>
              </tr>
              <tr>
                <td>予約日</td>
                <td>{{ $reserve->reserve->format('Y/m/d') }}</td>
                <td></td>
              </tr>
              <tr>
                <td>時間</td>
                <td>{{ $reserve->reserve->format('H:i') }}</td>
                <td></td>
              </tr>
              <tr>
                <td>人数</td>
                <td>{{ $reserve->member }}名</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3" class="res_change">
                  <button onclick="qr({{ $reserve->id }});" class="res_change_btn">QRコード</button>
                  <button onclick="res_form_set({{$reserve->id}},'{{ $reserve->reserve->format('Y/m/d') }}','{{ $reserve->reserve->format('H:i') }}',{{ $reserve->member }});" class="res_change_btn">予約を変更する</button>
                </td>
              </tr>
            </table>
          </div>
          @endforeach
        </div>
      </div>

      <div class="res_layout2">
        <h2 class="res_ttl_like">お気に入り店舗</h2>
        <div class="res_flex__item">
          @foreach($shops as $shop)
          <div class="card_grid">
            <div class="practice__card">
              <div class="card__img">
                <img src="{{ Storage::disk('s3')->url('public/img/shop_'. $shop->id .'/'. $shop->photo); }}" alt="">
              </div>
              <div class="card__content">
                <h2 class="card__ttl">{{ $shop->name }}</h2>
                <div class="tag">
                  <p class="card__area">#{{ $shop->area->area }}</p>
                  <p class="card__genre">#{{ $shop->genre->genre }}</p>
                </div>
                <div class="card_detail">
                  <a href="/detail?shop_id={{ $shop->id }}&path=mypage" class="card__cat">詳しく見る</a>
                  @auth
                  @if($shop->likes != '[]')
                  <img src="img/heart_red.png" onclick="like(event, {{ $shop->id }}, {{ $user->id }});" class="card__haert" />
                  @else
                  <img src="img/heart_gray.png" onclick="like(event, {{ $shop->id }}, {{ $user->id }});" class="card__haert" />
                  @endif
                  @endauth
                </div>
                <div class="card_review">
                  <a onclick="review_open({{ $shop->id }}, {{ $user->id }});">
                    評価する
                    @foreach($shop->reviews_user as $review)
                    ({{ $review->point }}点)
                    @endforeach
                  </a>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
</main>

<div class="modal-container">
  <div class="modal-body">
    <div onclick="modal_close();" class="modal-close">×</div>
    <div class="modal-content">
      <div class="modal-reserve">

        <form id="fm_reserve" method="post" action="/res_change">
          @csrf
          <table class="md_tbl">
            <tr>
              <td>
                <h2 class="reserve_ttl">～ 予約変更 ～</h2>
              </td>
            </tr>
            <tr>
              <td>
                <input type="text" id="date" name="res_date" placeholder="予約日" readonly />
                @error('res_date')
                <span class="error">{{ $errors->first('res_date') }}</span>
                @enderror
              </td>
            </tr>
            <tr>
              <td>
                <input type="text" id="time" name="res_time" onkeyup="input_erase()" data-time-format="H:i" placeholder="時間" />
                @error('res_time')
                <span class="error">{{ $errors->first('res_time') }}</span>
                @enderror
              </td>
            </tr>
            <tr>
              <td>
                <select id="member" onchange="reserve_data_set()" name="member" class="sel_member">
                  <option value="1">1名</option>
                  <option value="2">2名</option>
                  <option value="3">3名</option>
                  <option value="4">4名</option>
                  <option value="5">5名</option>
                  <option value="6">6名</option>
                </select>
                @error('member')
                <span class="error">{{ $errors->first('member') }}</span>
                @enderror
              </td>
            </tr>
          </table>
          <div class="reserve_btn_div">
            <input type="button" value="予約を変更する" onclick="reserve_change();" class="reserve_btn" />
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="reserve" name="reserve">
          </div>
        </form>
      </div>

      <div class="modal-review">
        <span class="modal-shop_name">評価</span>
        <form id="fm_review" method="post" action="/review">
          @csrf
          <div class="rate-form">
            <input id="star5" type="radio" name="point" value="5">
            <label for="star5">★</label>
            <input id="star4" type="radio" name="point" value="4">
            <label for="star4">★</label>
            <input id="star3" type="radio" name="point" value="3">
            <label for="star3">★</label>
            <input id="star2" type="radio" name="point" value="2">
            <label for="star2">★</label>
            <input id="star1" type="radio" name="point" value="1">
            <label for="star1">★</label>
          </div>
          <div class="comment-form">
            <p>コメント</p>
            <textarea id="comment" name="comment"></textarea>
          </div>
          <div class="modal-review_btn">
            <a href="#" onclick="review()">登録する</a>
          </div>
          <input type="hidden" id="rev_id" name="rev_id">
          <input type="hidden" id="rev_shop_id" name="rev_shop_id">
          <input type="hidden" id="rev_user_id" name="rev_user_id">
        </form>
      </div>

      <div class="modal-qr">
        <table class="qr_tbl">
          <tr>
            <th>店名：</th>
            <td></td>
          </tr>
          <tr>
            <th>予約日：</th>
            <td></td>
          </tr>
          <tr>
            <th>時間：</th>
            <td></td>
          </tr>
          <tr>
            <th>人数：</th>
            <td></td>
          </tr>
        </table>
        <div id="img-qr"></div>
        <p class="url"></p>
        <p class="qr_url">{{ url('/') }}</p>
      </div>
    </div>
  </div>

  <script src=" https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
  <script src="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.js"></script>
  <script src="{{ asset('js/mypage.js') }}"></script>
  <script src="{{ asset('js/menu.js') }}"></script>
  <script src="{{ asset('js/jquery.qrcode.min.js') }}"></script>

  <script>
    const shop_list = [
      @foreach($shops as $shop) {
        "id": "{{ $shop->id }}",
        "name": "{{ $shop->name }}",
        @if(isset($shop->reviews_user[0]) != null)
        "rev_id": "{{ $shop->reviews_user[0]->id }}",
        "point": "{{ $shop->reviews_user[0]->point }}",
        "comment": "{{ $shop->reviews_user[0]->comment }}"
        @else "rev_id": "",
        "point": "1",
        "comment": ""
        @endif
      },
      @endforeach
    ];

    const reserve_list = [
      @foreach($reserves as $reserve) {
        "id": "{{ $reserve->id }}",
        "name": "{{ $reserve->shop->name }}",
        "date": "{{ $reserve->reserve->format('Y/m/d') }}",
        "time": "{{ $reserve->reserve->format('H:i') }}",
        "member": "{{ $reserve->member }}"
      },
      @endforeach
    ];
  </script>
  @endsection