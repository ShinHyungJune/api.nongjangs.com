@component('mail::message')
    <div id="wrap">

        <main id="main" class="main">


            <table style="width: 100%;">
                <tr>
                    <td
                        style="text-align: center; padding-top: 96px; padding-bottom: 96px; border-radius: 24px; background-image: linear-gradient(116deg, #c3e1fca4 5%, #8ec5fc81 93%);">
                        <div class="popUp_logo" style="width: 165px; height: 33px; margin-bottom: 70px; margin: 0 auto;">
                            <img src="https://blueprep.com/images/Logo_C.png" alt="" style="display: block; width:100%;">
                        </div>
                        <p class="title"
                           style="margin-top: 70px; font-size: 40px; font-weight: bold; color: #202020; line-height: 1;">
                            Reset Password
                        </p>
                        <p class="title-sub" style="font-size: 20px; color: #202020; margin-top: 24px; line-height: 1;">
                            Click the <span style="font-weight: bold;">Reset password</span> button below:</p>
                        <a href="{{$item->getResetUrl()}}" class="btn-default verify-btn"
                           style="display:inline-block; padding:15px 36px; text-decoration: none; border-radius: 8px; box-shadow: 0 3px 24px 0 rgba(80, 87, 255, 0.08); border: unset; background-color: #fff; margin-top: 64px; cursor: pointer; font-size: 26px; color: #2c35fc;">
                            Reset password
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="offers-info" style="text-align: center; margin-top: 56px;">
                            Blueprep offers high-quality practice tests that are carefully designed after the actual tests,
                            <br/>at the most affordable price. We sincerely hope our service helps you achieve your goals.
                        </p>

                        <a class="domain-link"
                           style="display: block;margin: 70px auto 0; font-size: 20px; color: #202020; text-align: center"
                           href="{{config('app.client_url')}}">www.blueprep.com
                        </a>

                    </td>
                </tr>
            </table>
        </main>
    </div>
@endcomponent
