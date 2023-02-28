@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/auth.css">
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
        <a href="/auth/login">ログイン</a>
      </li>
      @endguest
    </ul>
  </nav>
</header>

<main>
  <section>
    <div class="form_layout">
      <form method="post" action="{{ route('post.login') }}?path={{ $path }}&shop_id={{ $shop_id }}&reserve={{ $reserve }}&date={{ $date }}&time={{ $time }}&member={{ $member }}&res_id={{ $res_id }}">
        <table class="form_table">
          @csrf
          <tr>
            <td colspan="2" class="tag">Login</td>
          </tr>

          <tr>
            <td></td>
            <td>
              <span class="error-message">{{ $text }}</span>
            </td>
          </tr>

          <tr>
            <td class="form_item form_margin">
              <img src="/img/email.png" class="icon">
            </td>
            <td class="form_item2 form_margin">
              <input type="text" name="email" placeholder="Email" class="form_input">
            </td>
          </tr>
          <tr>
            <td></td>
            <td>
              @error('email')
              <span class="error-message" id="Error-address">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <td class="form_item form_margin2">
              <img src="/img/password.png" class="icon">
            </td>
            <td class="form_item2 form_margin2">
              <input type="password" name="password" placeholder="Password" class="form_input">
            </td>
          </tr>
          <tr>
            <td></td>
            <td>
              @error('password')
              <span class="error-message" id="Error-address">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <td colspan="2" class="form_item3">
              <input type="submit" value="ログイン" class="form_submit">
            </td>
          </tr>
        </table>
      </form>
    </div>
  </section>
</main>

<script src="{{ asset('js/shop.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
@endsection