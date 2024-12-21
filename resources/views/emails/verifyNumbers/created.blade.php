@component('mail::message')
    <div class="mail-wrapper">
        <div class="mail-wrap">
            <div class="mail">
                <img src="{{asset("/images/logo.png")}}" alt="" class="logo">

                <div class="top">
                    <!--
                    <h3 class="title">인증번호발송</h3>
                    -->
                    <p class="body">인증번호가 도착하였습니다.
                        해당 인증번호를 입력해 이메일을 인증해주세요.</p>
                </div>

                <h3 class="title-main">인증번호 : {{$item->number}}</h3>

                <div class="btns">
                    <a href="{{config("app.client_url")}}" class="m-btn type01">홈페이지 바로가기</a>
                </div>

            </div>
            <div class="footer">
                <img src="{{asset("/images/logo-white.png")}}" alt="">

                <p class="body">대표자 : 박성재</p>
                <div class="body">주소 : 서울특별시 강남구 언주로 702(경남제약타워) 4층</div>
                <div class="body">고객센터 : help@knsquare.co.kr</div>
                <div class="body">개인정보보호책임자 : 박성재</div>
                <div class="body">사업자등록번호 : 708-87-02535</div>
                <div class="body">통신판매업신고번호 : 제 2022-서울강남-00900 호</div>
                <p class="body">Copyright 2023 경남제약스퀘어(주) All rights reserved.</p>
            </div>
        </div>
    </div>
@endcomponent
