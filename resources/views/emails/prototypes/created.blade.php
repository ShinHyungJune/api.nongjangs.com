@component('mail::message')
    <div class="mail-wrap">
        <div class="mail-box">
            <div class="logo-wrap">
                <img src="{{config('app.url').'/images/logo-big.png'}}" alt="">
            </div>
            <h2>시안 제작이 완료되었습니다.</h2>
            <div class="content-wrap">
                1. "확인하기" 버튼을 눌러 시안을 확인해주세요.

                2. 시안이 마음에 "확정" 버튼을 눌러주세요.

                3. 수정사항이 있다면 "시안수정 요청"에 요청사항을 입력해주세요.
            </div>

            <h3>주문자 정보</h3>
            <div class="table-box">
                <div class="table-body">
                    <table>
                        <colgroup>
                            <col style="width:30%">
                            <col style="width:70%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <th>주문상품</th>
                            <td>{{$prototype->presetProduct->product->title}}</td>
                        </tr>
                        <tr>
                            <th>주문자 명</th>
                            <td>{{$prototype->presetProduct->preset->order->buyer_name}}</td>
                        </tr>
                        <tr>
                            <th>휴대폰</th>
                            <td>{{$prototype->presetProduct->preset->order->buyer_contact}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="btn-wrap">
                <a href="{{config('app.client_url').'/prototypes?preset_product_uuid='.$prototype->presetProduct->uuid}}" class="btn">확인하기</a>
            </div>
        </div>
        <div class="mail-ft">
            <ul>
                <li>회사명 : 언더독 컴퍼니 </li>
                <li>대표자명 : 김수철 </li>
                <li>주소 : 서울특별시 중구 마른내로 61-1, 2층 206호(안현동1가) </li>
                <li>사업자 등록번호 : 112-33-31895 </li>
                <li>통신판매번호 2023-서울강동-0202</li>
            </ul>
            <p>Copyright 언더독 컴퍼니.All rights reserved.</p>
        </div>
    </div>
@endcomponent
