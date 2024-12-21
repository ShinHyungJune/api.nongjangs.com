@component('mail::message')
    <div class="mail-wrap">
        <div class="mail-box">
            <div class="logo-wrap">
                <img src="{{config('app.url').'/images/logo-big.png'}}" alt="">
            </div>
            <h2>시안피드백이 접수되었습니다.</h2>
            <div class="content-wrap">
                1. "확인하기" 버튼을 눌러 피드백 내용을 확인해주세요.

                2. 피드백 내용을 토대로 생성한 시안을 수정해주세요.

                3. 시안이 수정되면 사용자에게 알림메일이 발송됩니다.
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
                            <td>{{$feedback->presetProduct->product->title}}</td>
                        </tr>
                        <tr>
                            <th>주문자 명</th>
                            <td>{{$feedback->presetProduct->preset->order->buyer_name}}</td>
                        </tr>
                        <tr>
                            <th>휴대폰</th>
                            <td>{{$feedback->presetProduct->preset->order->buyer_contact}}</td>
                        </tr>
                        <tr>
                            <th>피드백 내용</th>
                            <td style="white-space: pre-line;">{{$feedback->description}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="btn-wrap">
                <a href="{{config('app.client_url').'/admin/presetProducts/'.$feedback->presetProduct->id}}" class="btn">확인하기</a>
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
