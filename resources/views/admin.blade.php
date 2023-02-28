@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/admin.css">
<link rel="stylesheet" href="/css/menu.css">
<link rel="stylesheet" href="/css/modal.css">
<link rel="stylesheet" href="/css/paginate.css">

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
  <section class="admin_menu">
    <div class="admin_menu_layout">
      <p class="admin_menu_ttl">管理者ページ</p>
      <nav class="admin_menu_nav">
        <ul class="admin_menu_ul">
          <li>店舗管理</li>
        </ul>
      </nav>
    </div>
  </section>

  <section class="admin_form_layout">
    <div class="admin_form_screen">
      <div class="admin_form_header">
        <button class="shop_register">新規登録</button>

        <form id="search" method="get" action="{{ route('admin.search') }}">
          @csrf
          <ul class="admin_search">
            <li>
              <p class="admin_search_ttl">検索：</p>
            </li>
            <li>
              <div class="search__">
                <input type="text" name="tx_search" onkeydown="if(event.keyCode==13){search();}" autocomplete="given-name" value="{{ $form['tx_search'] }}" placeholder="店名">
              </div>
            </li>
            <li>
              <select id="sel_area" name="sel_area" onchange="search();" class="sel_area">
                <option value="">都道府県</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}" @if($area->id===(int)$form['sel_area']) selected @endif>{{ $area->area }}</option>
                @endforeach
              </select>
            </li>
            <li>
              <select id="sel_genre" name="sel_genre" onchange="search();" class="sel_genre">
                <option value="">ジャンル</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" @if($genre->id===(int)$form['sel_genre']) selected @endif>{{ $genre->genre }}</option>
                @endforeach
              </select>
            </li>
          </ul>
        </form>
      </div>

      <div class="paginate">
        {{$shops->appends(['tx_search'=>$form['tx_search']])->appends(['sel_area'=>$form['sel_area']])->appends(['sel_genre'=>$form['sel_genre']])->links()}}
      </div>

      <table class="admin_form_tbl">
        <tr>
          <th></th>
          <th>店舗ID</th>
          <th>店名</th>
          <th>都道府県</th>
          <th>ジャンル</th>
          <th>お店の概要</th>
          <th>Photo</th>
          <th>店舗担当者</th>
          <th>店舗メールアドレス</th>
          <th>店舗〒</th>
          <th>店舗住所</th>
          <th>店舗TEL</th>
          <th>表示</th>
        </tr>
        @foreach($shops as $shop)
        <tr>
          <td>
            <button onclick="shop_set({{ $shop->id }}, 'set');" class="shop_set">参照</button>
          </td>
          <td>{{ $shop->id }}</td>
          <td>{{ $shop->name }}</td>
          <td>
            @isset($shop->area->area)
            {{ $shop->area->area }}
            @endisset
          </td>
          <td>
            @isset($shop->genre->genre)
            {{ $shop->genre->genre }}
            @endisset
          </td>
          <td>{{ Str::limit($shop->overview, 30, '...') }}</td>
          <td>{{ $shop->photo }}</td>
          <td>{{ $shop->manager }}</td>
          <td>
            <a onclick="shop_set({{ $shop->id }}, 'mail');" class="shop_mail">{{ $shop->email }}</a>
          </td>
          <td>{{ $shop->post_code }}</td>
          <td>{{ $shop->address }}</td>
          <td>{{ $shop->tel }}</td>
          <td style="text-align:center;">
            @if($shop->display == 1)
            する
            @else
            しない
            @endif
          </td>
        </tr>
        @endforeach
      </table>
    </div>
  </section>
</main>

<div class="modal-container">
  <div class="modal-body">
    <div onclick="modal_close();" class="modal-close">×</div>
    <div id="admin_reg_layout" class="modal-content">
      <h2 class="admin_ttl">【 新規登録 】</h2>
      <span class="required">※は入力必須</span>
      <p class="shop_data">
        店舗ID：<span id="span_id"></span>　
      </p>
      <form id="fm_admin_reg" method="POST" action="{{ route('admin.register') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id" value="{{ old('id') }}">
        <table class="admin_reg_tbl">
          <tr>
            <th>
              <span class="required">※</span>店名
            </th>
            <td>
              <input type="text" id="name" name="name" value="{{ old('name') }}">
              @error('name')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>　都道府県</th>
            <td>
              <select id="area" name="area">
                <option value="">都道府県</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}" @if($area->id==old('area')) selected @endif>{{ $area->area }}</option>
                @endforeach
              </select>
            </td>
          </tr>
          <tr>
            <th>　ジャンル</th>
            <td>
              <select id="genre" name="genre">
                <option value="">ジャンル</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" @if($genre->id==old('genre')) selected @endif>{{ $genre->genre }}</option>
                @endforeach
              </select>
            </td>
          </tr>
          <tr>
            <th>　お店の概要</th>
            <td>
              <p class="overview_ttl">
                文字数：<span id="txtlmt">0</span>
                @error('overview')
                <span class="error">　{{ $message }}</span>
                @enderror
              </p>
              <textarea id="tx_overview" name="overview" maxlength="200">{{ old('overview') }}</textarea>
            </td>
          </tr>
          <tr>
            <th>　Photo</th>
            <td>
              <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.bmp">
              @error('photo')
              <p class="error error_p">{{ $message }}</p>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              　店舗担当者名
            </th>
            <td>
              <input type="text" id="manager" name="manager" value="{{ old('manager') }}">
              @error('manager')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>店舗ﾒｰﾙｱﾄﾞﾚｽ
            </th>
            <td>
              <input type="text" id="email" name="email" value="{{ old('email') }}">
              @error('email')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              　店舗〒
            </th>
            <td>
              <input type="text" id="post_code" name="post_code" value="{{ old('post_code') }}">
              @error('post_code')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              　店舗住所
            </th>
            <td>
              <input type="text" id="address" name="address" value="{{ old('address') }}" class="address">
              @error('address')
              <p class="error error_p">{{ $message }}</p>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              　店舗TEL
            </th>
            <td>
              <input type="text" id="tel" name="tel" value="{{ old('tel') }}">
              @error('tel')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>　表示</th>
            <td>
              <select id="display" name="display">
                <option value="0" selected>しない</option>
                <option value="1">する</option>
              </select>
              <span class="display_message">新規登録の場合は、「しない」を選択して下さい</span>
            </td>
          </tr>
        </table>
        <div class="register_layout">
          <input type="button" value="登録する" class="register_btn">
        </div>
      </form>
    </div>

    <div id="admin_reg_complete_layout" class="modal-content">
      @if(request('kbn')==='new_record')
      <div class="admin_mail_title">
        <h2>【 新規登録完了 】</h2>
        <p>　以下の内容を店舗担当者にお知らせください。</p>
      </div>
      @endif
      @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
      @endif
      @if ($errors->any())
      <div class="alert alert-danger">
        メール送信できませんでした
      </div>
      @endif
      <form method="POST" action="/sendmail">
        @csrf
        <input type="hidden" required name="from" value="tak.it.easy1@gmail.com">
        <div class="form-group">
          <label>件名</label>
          @if(request('kbn')==='new_record')
          <input required name="subject" value="{{ request('subject') }}" class="form-control">
          @else
          <input required id="mail_subject" name="subject" class="form-control">
          @endif
        </div>
        <div class="form-group">
          <label>送信先</label>
          @if(request('kbn')==='new_record')
          <input required name="user" value="{{ $shop_data[0]->email }}" class=" form-control">
          @else
          <input required id="mail_user" name="user" class=" form-control">
          @endif
        </div>
        <div class="form-group">
          <label>本文</label>
          <textarea required name="body" class="form-control" rows="10">
@if(request('kbn')==='new_record')
{{ $shop_data[0]->name }} ご担当者様


お世話になります。
この度はReseへのご利用登録、誠にありがとうございます。


以下のURLからユーザー登録をし店舗登録をして下さい。

店舗登録URL: {{ url('/') }}/owner?shop_id={{ $shop_data[0]->id }}&password={{ $shop_data[0]->password }}&kbn=new_shop&menu=1


よろしくお願い致します。
@endif
</textarea>
        </div>
        <div class="register_layout">
          <input type="submit" value="メール送信する" class="mail_btn">
        </div>
      </form>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src=" {{ asset('js/admin.js') }}"></script>
  <script src=" {{ asset('js/menu.js') }}"></script>
  <script>
    const shop_list = [
      @foreach($shops as $shop) {
        "id": "{{ $shop->id }}",
        "password": "{{ $shop->password }}",
        "name": "{{ $shop->name }}",
        "area_id": "{{ $shop->area_id }}",
        "genre_id": "{{ $shop->genre_id }}",
        "overview": "{{ $shop->overview }}",
        "photo": "{{ $shop->photo }}",
        "manager": "{{ $shop->manager }}",
        "email": "{{ $shop->email }}",
        "post_code": "{{ $shop->post_code }}",
        "address": "{{ $shop->address }}",
        "tel": "{{ $shop->tel }}",
        "display": "{{ $shop->display }}",
      },
      @endforeach
    ];
  </script>
  @foreach ($errors->all() as $error)
  <script>
    $('.modal-reserve').css('display', '');
    $('.modal-review').css('display', 'none');
    $('.modal-container').addClass('active');
    $('#admin_reg_layout').css('display', '');
    $('#admin_reg_complete_layout').css('display', 'none');
  </script>
  @break
  @endforeach
  @if($shop_data !== '')
  <script>
    $('.modal-reserve').css('display', '');
    $('.modal-review').css('display', 'none');
    $('.modal-container').addClass('active');
    $('#admin_reg_layout').css('display', 'none');
    $('#admin_reg_complete_layout').css('display', '');
  </script>
  @endif
  @endsection