@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/thanks.css">
<link rel="stylesheet" href="/css/menu.css">

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
    <div class="form_layout">
      <h2>{{ $message }}</h2>
      <a href="/" class="login_btn">ホームへ戻る</a>
    </div>
  </section>
</main>
@endsection