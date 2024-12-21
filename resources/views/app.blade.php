<!DOCTYPE html>
<html>
<head>
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>{{config("app.name")}}</title>
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">

    <!-- 캘린더 -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/locales-all.min.js'></script>

    <link rel="stylesheet" type="text/css" href="{{asset("css/swiper.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/datepicker.min.css")}}">

    <link rel="stylesheet" type="text/css" href="{{asset("css/common.css?v=".\Carbon\Carbon::now()->format("YmdHi"))}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/style.css?v=".\Carbon\Carbon::now()->format("YmdHi"))}}">

    <!-- 노바 텍스트 에디터 스타일 -->
    <link href="https://cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">

    <!-- TOAST UI Editor CDN(JS) -->
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
    <!-- TOAST UI Editor CDN(CSS) -->
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />

    <!-- 네이버 검색등록 -->
    <meta name="naver-site-verification" content="" />
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{config("app.name")}}">
    <meta property="og:url" content="">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{"app.name"}}">
    <meta name="twitter:title" content="{{config("app.name")}}">
    <meta name="twitter:domain" content="{{config("app.name")}}">

    <!-- 구글검색등록 -->
    <meta name="google-site-verification" content="" />

    <!-- 카카오 주소찾기 -->
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=1ef231cd0a39ca2c5c751d0ab9d7c5ee&libraries=services"></script>
    <script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>
    <script src="{{asset('js/jquery.js')}}"></script>

    <script src="{{asset('js/swiper.min.js')}}"></script>
    <script src="{{asset('js/common.js')}}"></script>
    <script src="{{ asset('/js/app.js?v='.\Carbon\Carbon::now()->format("YmdHi")) }}" defer></script>
</head>
<style>
    html, body {
        padding:100px;
        background-color:#f5f5f5;
    }
    .area {
        display: block;
        padding:60px 0; padding-top:40px; padding-bottom:0;
        background-color:#fff; border-radius:10px;
    }
    table {width: 100%;
    }
    th {
        padding:10px;
        font-weight:500;
        background-color:#ffc043; color:#000;
    }
    td {
        padding:10px; word-break: break-all;
        border-bottom:1px solid #e1e1e1;
    }
    .title {
        margin-bottom:12px;
        font-size:24px; text-align: center; font-weight:bold;
    }
    .btns {
        display: flex; justify-content: center;
        margin-bottom:32px;
    }
    .btn {
        padding:12px 20px;
        border-radius:5px;
        background-color:#ffc043; color:#000;
        box-shadow:0px 3px 6px rgba(0,0,0,0.16);
        text-decoration: none;
    }
</style>
<body>

</body>
</html>
