@component('mail::message')
    <div id="wrap">

        <main id="main" class="main">
            <table>
                <tr>
                    <td>
                        <a href="{{config('app.client_url')}}/admin/influencers/socials" class="popUp_logo">
                            홈페이지 바로가기
                        </a>
                        <p class="title-sub">{{$item->instagram}}</p>

                    </td>
                </tr>
            </table>
        </main>
    </div>


@endcomponent
