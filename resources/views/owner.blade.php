@extends('layouts.default')

@section('main')
<link rel="stylesheet" href="/css/owner.css">
<link rel="stylesheet" href="/css/menu.css">
<link rel="stylesheet" href="/css/paginate.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">

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
  <section class="owner_menu">
    <div class="owner_menu_layout">
      <p class="owner_menu_ttl">店舗管理ページ</p>
      <nav class="owner_menu_nav">
        <ul class="owner_menu_ul">
          <li onclick="menu(0,{{ $user->shop_id }});">予約情報</li>
          <li onclick="menu(1,{{ $user->shop_id }});">登録情報</li>
        </ul>
      </nav>
    </div>
  </section>

  <section class="owner_section">
    @if($menu=="0")
    <div class="res_form_screen">
      <div class="res_form_header">
        <form id="search" method="get">
          @csrf
          <ul class="res_search">
            <li>
              <p class="res_search_ttl">検索：</p>
            </li>
            <li>
              <input type="text" id="tx_search" name="tx_search" autocomplete="given-name" value="{{ request()->query('name') }}" placeholder="お客様名">
            </li>
            <li>
              <input type="text" id="search_date1" name="search_date1" value="{{ request()->query('date1') }}" placeholder="予約日" class="search_date" />
              ～
              <input type="text" id="search_date2" name="search_date2" value="{{ request()->query('date2') }}" placeholder="予約日" class="search_date" />
            </li>
            <li>
              <span class="search_name">確認</span>
              <select id="search_check" name="search_check">
                <option value="">全て</option>
                <option value="0" @if(request()->query('check')==="0") selected @endif>未確認</option>
                <option value="1" @if(request()->query('check')==="1") selected @endif>確認済み</option>
              </select>
            </li>
            <li>
              <span class="search_name">予約状況</span>
              <select id="search_situation" name="search_situation">
                <option value="">全て</option>
                <option value="1" @if(request()->query('situation')==="1") selected @endif>予約中</option>
                <option value="2" @if(request()->query('situation')==="2") selected @endif>予約変更</option>
                <option value="3" @if(request()->query('situation')==="3") selected @endif>来店済み</option>
                <option value="0" @if(request()->query('situation')==="0") selected @endif>キャンセル</option>
              </select>
            </li>
            <li>
              <input type="button" id="search_btn" value="検索する" onclick="search({{ $user->shop_id }})">
            </li>
          </ul>
        </form>
      </div>

      <div class="paginate">
        {{$reserves->appends(request()->query())->links()}}
      </div>

      <div class="reserve_layout">
        <table class="reserve_tbl">
          <tr>
            <th>お客様名</th>
            <th>予約日</th>
            <th>時間</th>
            <th>人数</th>
            <th>メールアドレス</th>
            <th>確認</th>
            <th>予約状況</th>
            <th>金額</th>
          </tr>
          @foreach($reserves as $reserve)
          <tr>
            <td>{{ $reserve->reserves[0]->name }}</td>
            <td>{{ $reserve->reserve->format('Y/m/d') }}</td>
            <td>{{ $reserve->reserve->format('H:i') }}</td>
            <td>{{ $reserve->member }}</td>
            <td>{{ $reserve->reserves[0]->email }}</td>
            <td>
              <input type="checkbox" onclick="check({{ $reserve->id }}, this);" @if($reserve->check === 1) checked @endif>
            </td>
            <td>
              <select onchange="situation({{ $reserve->id }}, this);">
                <option value="1" @if((int)$reserve->situation === 1) selected @endif>予約中</option>
                <option value="2" @if($reserve->situation === 2) selected @endif>予約変更</option>
                <option value="3" @if($reserve->situation === 3) selected @endif>来店済み</option>
                <option value="0" @if($reserve->situation === 0) selected @endif>キャンセル</option>
              </select>
            </td>
            <td>
              <input type="text" name="price" value="{{ $reserve->price }}">
            </td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>

    @elseif($menu=="1")
    <div class="form_layout">
      <h2 class="owner_ttl">～ 登録情報 ～</h2>
      <p class="required message">※は入力必須です</@>
      <p class="shop_data">
        店舗ID：<span id="span_id">{{ $shop->id }}</span>　
      </p>
      <form id="fm_owner_reg" method="POST" action="{{ route('owner.register') }}?menu=1" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id" name="id" value="{{ $shop->id }}">
        <table class="owner_reg_tbl">
          <tr>
            <th>
              <span class="required">※</span>店名
            </th>
            <td>
              <input type="text" id="name" name="name" value="{{ $shop->name }}">
              @error('name')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>都道府県
            </th>
            <td>
              <select id="area" name="area">
                <option value="">都道府県</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}" @if($area->id==$shop->area_id) selected @endif>{{ $area->area }}</option>
                @endforeach
              </select>
              @error('area')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>ジャンル
            </th>
            <td>
              <select id="genre" name="genre">
                <option value="">ジャンル</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" @if($genre->id==$shop->genre_id) selected @endif>{{ $genre->genre }}</option>
                @endforeach
              </select>
              @error('genre')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>お店の概要
            </th>
            <td>
              <p class="overview_ttl">
                文字数：<span id="txtlmt">0</span>
                @error('overview')
                <span class="error">　{{ $message }}</span>
                @enderror
              </p>
              <textarea id="tx_overview" name="overview" maxlength="200">{{ $shop->overview }}</textarea>
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>Photo
            </th>
            <td>
              <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.bmp">
              @error('photo')
              <p class="error error_p">{{ $message }}</p>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>担当者名
            </th>
            <td>
              <input type="text" id="manager" name="manager" value="{{ $shop->manager }}">
              @error('manager')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>メールアドレス
            </th>
            <td>
              <input type="text" id="email" name="email" value="{{ $shop->email }}">
              @error('email')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>郵便番号
            </th>
            <td>
              <input type="text" id="post_code" name="post_code" value="{{ $shop->post_code }}">
              @error('post_code')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>住所
            </th>
            <td>
              <input type="text" id="address" name="address" value="{{ $shop->address }}" class="address">
              @error('address')
              <p class="error error_p">{{ $message }}</p>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>TEL
            </th>
            <td>
              <input type="text" id="tel" name="tel" value="{{ $shop->tel }}">
              @error('tel')
              <span class="error">{{ $message }}</span>
              @enderror
            </td>
          </tr>
          <tr>
            <th>
              <span class="required">※</span>表示
            </th>
            <td>
              <select id="display" name="display">
                <option value="0">しない</option>
                <option value="1" selected>する</option>
              </select>
            </td>
          </tr>
        </table>
        <div class="register_layout">
          <input type="button" value="登録する" class="register_btn">
        </div>
      </form>
    </div>
    @endif
  </section>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<script src=" {{ asset('js/owner.js') }}"></script>
<script src=" {{ asset('js/menu.js') }}"></script>
@endsection