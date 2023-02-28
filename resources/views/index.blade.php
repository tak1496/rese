@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/index.css">
<link rel="stylesheet" href="/css/menu.css">

<header class="header">
  <div class="rese_content">
    <img src="/img/rese.png" class="rese_img">
    <h1>Rese</h1>
  </div>

  <div class="login">
    @auth
    <p class="user_name">ログイン：{{ $user->name }}さん</p>
    @endauth
  </div>
  <nav>
    <img src="/img/search_btn.png" id="search_img_btn" class="search_img_btn">
    <div id="search_menu" class="search_menu">
      <form id="search" method="post" action="{{ route('form.index') }}">
        @csrf
        <ul>
          <li>
            <select id="sel_area" name="sel_area" onchange="search();" class="sel_area">
              <option value="">都道府県</option>
              @foreach($areas as $area)
              @if($area->area->id == $form['sel_area'])
              <option value="{{ $area->area->id }}" selected>{{ $area->area->area }}</option>
              @else
              <option value="{{ $area->area->id }}">{{ $area->area->area }}</option>
              @endif
              @endforeach
            </select>
          </li>
          <li>
            <select id="sel_genre" name="sel_genre" onchange="search();" class="sel_genre">
              <option value="">ジャンル</option>
              @foreach($genres as $genre)
              @if($genre->id == $form['sel_genre'])
              <option value="{{ $genre->id }}" selected>{{ $genre->genre }}</option>
              @else
              <option value="{{ $genre->id }}">{{ $genre->genre }}</option>
              @endif
              @endforeach
            </select>
          </li>
          <li>
            <div class="search__">
              <img src="/img/search.png" class="search_img">
              店名：<input type="text" name="tx_search" onkeydown="if(event.keyCode==13){search();}" autocomplete="given-name" value="{{ $form['tx_search'] }}">
            </div>
          </li>
        </ul>
      </form>
    </div>

    <div class="menu" id="menu">
      <span class="menu-line-top"></span>
      <span class="menu-line-middle"></span>
      <span class="menu-line-bottom"></span>
    </div>

  </nav>

  <nav class="drawer-nav" id="drawer-nav">
    <ul class="drawer-nav-list">
      <li class="drawer-nav-item">
        <a href="#" onclick="document.getElementById('menu').click();">ホーム</a>
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
  <section>
    <div class="flex__item">
      @foreach($shops as $shop)
      <div class="card_grid">
        <div class="practice__card">
          <div class="card__img">
            {{--<img src="{{ asset('storage/img/shop_'. $shop->id .'/'. $shop->photo ) }}" alt="" /> --}}
            <img src="{{ Storage::disk('s3')->url('public/img/shop_'. $shop->id .'/'. $shop->photo); }}">
          </div>
          <div class="card__content">
            <h2 class="card__ttl">{{ $shop->name }}</h2>
            <div class=" tag">
              <a href="#" onclick="link_search({{$shop->area->id}},'area');" class="card__area">#{{ $shop->area->area }}</a>
              <a href="#" onclick="link_search({{$shop->genre->id}},'genre');" class="card__genre">#{{ $shop->genre->genre }}</a>
            </div>
            <div class="card_detail">
              <a href="/detail?shop_id={{ $shop->id }}&path=form.index" class="card__cat"> 詳しく見る </a>
              @auth
              @if($shop->likes != '[]')
              <img src="img/heart_red.png" onclick="like(event, {{ $shop->id }}, {{ $user->id }});" class="card__haert" />
              @else
              <img src="img/heart_gray.png" onclick="like(event, {{ $shop->id }}, {{ $user->id }});" class="card__haert" />
              @endif
              @endauth
            </div>
            <div class="card_review">
              <p class="result-rating-rate">
                <span class="star5_rating" data-rate="{{ Round($shop->reviews_shop_avg_point,1) }}"></span>
                <span class="number_rating">{{ Round($shop->reviews_shop_avg_point,1) }}</span>
                <span>({{ $shop->reviews_shop_count }}件)</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </section>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('js/shop.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
@endsection