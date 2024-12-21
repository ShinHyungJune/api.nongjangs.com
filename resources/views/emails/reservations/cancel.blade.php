@component('mail::message')

    <div class="mail-wrapper">
        <div class="mail-wrap">
            <div class="mail">
                <img src="{{asset("/images/logo.png")}}" alt="" class="logo">

                <div class="top">
                    <h3 class="title">{{__("validation.reservations.cancel")}}</h3>
                    <p class="body">{{__("validation.reservations.cancel_description")}}</p>
                </div>

                <h3 class="title-main">{{__("validation.attributes.reservation_uuid")}} : {{$item->uuid}}</h3>

                <table class="m-table type01">
                    <colgroup>
                        <col style="width:20%">
                        <col style="width:80%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>CLASS</th>
                        <td>{{$item->type_airplane}}</td>
                    </tr>
                    <tr>
                        <th>LOCATION</th>
                        <td>{{$item->location}}</td>

                    </tr>
                    <tr>
                        <th>PERSON</th>
                        <td>{{$item->count_person}}</td>

                    </tr>
                    <tr>
                        <th>PURPOSE</th>
                        <td>{{$item->purpose}}</td>
                    </tr>
                    <tr>
                        <th>PARTS</th>
                        <td>{{\App\Models\Arr::getArrayToString($item->parts->pluck("title"))}}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="btns">
                    <a href="{{config("app.client_url")."/reservations/".$item->uuid}}" class="m-btn type01">Go to Check</a>
                </div>

            </div>
            <div class="footer">
                <img src="{{asset("/images/logo-white.png")}}" alt="">

                <p class="body">Daniel jehoon Choi CEO</p>
                <div class="body">Address : A, 31F S 56, 323, Incheon tower-daero, Yeonsu-gu, Incheon, Republic of Korea</div>
                <div class="body">Business Registration Number 0507-1412-2659</div>
                <p class="body">© 2023 cleve Co. All rights reserved.</p>
            </div>
        </div>
    </div>
@endcomponent
