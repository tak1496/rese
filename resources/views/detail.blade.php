@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/detail.css">
<link rel="stylesheet" href="/css/menu.css">
<link rel="stylesheet" href="/css/modal.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
<link rel="stylesheet" href="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.css">
<script src="https://kit.fontawesome.com/b8a7fea4d4.js"></script>

<header class="header">
  <div class="rese_content">
    <img src="/img/rese.png" class="rese_img">
    <h1>Rese</h1>
  </div>

  <div class="menu" id="menu">
    <span class="menu-line-top"></span>
    <span class="menu-line-middle"></span>
    <span class="menu-line-bottom"></span>
  </div>

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
        <a href="/auth/login?path=form.index&shop_id={{ $shop->id }}">ログイン</a>
      </li>
      @endguest
    </ul>
  </nav>
</header>

<main>
  <section>

    <div class="detail_layout">
      <div class="detail_layout1">

        <div>
          <div class="shop_ttl">
            <button onclick="back('{{ route($path) }}')" class="back_btn">＜</button>
            <h2 class="shop_name">{{ $shop->name }}</h2>
          </div>

          <img src="{{ Storage::disk('s3')->url('public/img/shop_'. $shop->id .'/'. $shop->photo); }}" class="shop_photo" />
          
          <div class="shop_cat">
            <span>#{{ $shop->area->area }}</span>
            <span>#{{ $shop->genre->genre }}</span>
          </div>

          <div class="shop_overview">{{ $shop->overview }}</div>

        </div>

        <hr />

        <div class="review_layout">
          <a href="#" class="review_display">レビューを非表示</a>
          <div class="review_list">
            @foreach($reviews as $review)
            <div class="review">
              <p>{{ $review->reviews[0]->name }}さんの投稿</p>
              <p class="review_date">投稿日：{{ $review->created_at->format('Y/m/d') }}</p>
              <div class="card_review">
                <p class="result-rating-rate">
                  <span class="star5_rating" data-rate="{{ Round($review->point,1) }}"></span>
                  <span class="number_rating">{{ Round($review->point,1) }}</span>
                </p>
              </div>
              <p>{{ $review->comment }}</p>
            </div>
            @endforeach
          </div>
        </div>

      </div>

      <div class="detail_layout2">

        <form id="fm_reserve" method="post" action="{{ route('reserve') }}?path=reserve&shop_id={{ $shop->id }}">
          @csrf
          <input type="hidden" name="shop_id" value="{{ $shop->id }}">
          <input type="hidden" name="user_id" value="@if(isset($user->id)) {{ $user->id }} @endif">
          <input type="hidden" id="reserve" name="reserve" value="{{ $reserve }}">
          <div class="reserve_layout">
            <h2 class="reserve_ttl">Reserve</h2>

            <p class="reserve_form">
              <input type="text" id="date" name="res_date" value="{{ $date }}" onchange="reserve_data_set(this.id)" placeholder="予約日" readonly />
              @error('res_date')
            <p class="error">{{ $errors->first('res_date') }}</p>
            @enderror
            </p>

            <p class=" reserve_form">
              <input type="text" id="time" name="res_time" value="{{ $time }}" onchange="reserve_data_set(this.id)" onkeyup="input_erase()" data-time-format="H:i" placeholder="時間" />
              @error('res_time')
            <p class="error">{{ $errors->first('res_time') }}</p>
            @enderror
            </p>

            <p class="reserve_form">
              <select id="member" onchange="reserve_data_set(this.id)" name="member" class="sel_member">
                <option value="" disabled selected style="display:none;">人数</option>
                @for ($i = 1; $i < 7; $i++) <option value="{{ $i }}" @if($i==$member) selected @endif>{{ $i }}名</option>
                  @endfor
              </select>
              @error('member')
            <p class="error">{{ $errors->first('member') }}</p>
            @enderror
            </p>

            <div class="reserve_detail">
              <table class="reserve_detail_tbl">
                <tr>
                  <th>店名</th>
                  <td>{{ $shop->name }}</td>
                </tr>
                <tr>
                  <th>予約日</th>
                  <td id="tx_date"></td>
                </tr>
                <tr>
                  <th>時間</th>
                  <td id="tx_time"></td>
                </tr>
                <tr>
                  <th>人数</th>
                  <td id="tx_member"></td>
                </tr>
              </table>
            </div>

            <div class="reserve_chk">
              @auth
              <a href="#" onclick="reserve_chk();" class="reserve_chk_btn">予約をする</a>
              @endauth
              @guest
              <p class="reserve_login">予約をするにはログイン、または新規登録をして下さい。</p>
              <a href="#" onclick="reserve_login('login',{{ $shop->id }});" class="reserve_chk_btn">ログイン</a>
              <a href="#" onclick="reserve_login('register',{{ $shop->id }});" class="reserve_chk_btn">新規登録</a>
              @endguest
            </div>

          </div>
        </form>
      </div>
    </div>

  </section>
</main>

<div class="modal-container">
  <div class="modal-body">
    <div onclick="modal_close();" class="modal-close">×</div>
    <div class="modal-content">
      <p>以下の内容で予約致します。</p>
      <table class="md_tbl">
        <tr>
          <td class="detail_content">店名</td>
          <td>{{ $shop->name }}</td>
        </tr>
        <tr>
          <td class="detail_content">予約日</td>
          <td id="md_date"></td>
        </tr>
        <tr>
          <td class="detail_content">時間</td>
          <td id="md_time"></td>
        </tr>
        <tr>
          <td class="detail_content">人数</td>
          <td id="md_member" class="detail"></td>
        </tr>
      </table>
      <div class="reserve_btn_div">
        <input type="button" value="予約をする" onclick="reserve();" class="reserve_btn" />
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<script src="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.js"></script>
<script src=" {{ asset('js/detail.js') }}"></script>
<script src=" {{ asset('js/menu.js') }}"></script>
<script>
  @isset($date)
  reserve_data_set('date');
  @endisset
  @isset($time)
  reserve_data_set('time');
  @endisset
  @isset($member)
  reserve_data_set('member');
  @endisset
</script>
@endsection