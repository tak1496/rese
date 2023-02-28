@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/menu.css">
<link rel="stylesheet" href="/css/owner_reserve.css">

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
        <a href="">マイページ</a>
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
        <a href="/auth/login?path=detail&shop_id={{ $shop->id }}">ログイン</a>
      </li>
      @endguest
    </ul>
  </nav>
</header>

<main>
  <section>
    <div class="layout">
      <h2 class="title">～ 予約確認 ～</h2>
      <table class="qr_tbl">
        <tr>
          <th>店名：</th>
          <td>{{ $reserve->shop->name }}</td>
        </tr>
        <tr>
          <th>お客様名：</th>
          <td>{{ $reserve->reserves[0]->name }} 様</td>
        </tr>
        <tr>
          <th>予約日：</th>
          <td>{{ $reserve->reserve->format('Y/m/d') }}</td>
        </tr>
        <tr>
          <th>時間：</th>
          <td>{{ $reserve->reserve->format('H:i') }}</td>
        </tr>
        <tr>
          <th>人数：</th>
          <td>{{ $reserve->member }}名</td>
        </tr>
        <tr>
          <td colspan="2" class="qr_btn_td">
            @if($reserve->situation!==3)
            <button onclick="owner_reserve({{ $reserve->id }});" class="qr_btn">ご来店登録</button>
            @else
            <p>※ご来店済みです</p>
            @endif
          </td>
        </tr>
      </table>
    </div>
  </section>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('js/owner.js') }}"></script>
<script src=" {{ asset('js/menu.js') }}"></script>
@endsection