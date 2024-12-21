<template>
    <footer class="footer">
        <div class="wrap">
            <div class="top">
                <img src="/images/logo-footer.png" alt="" class="logo">
                <h3 class="title">강남건설 · 강남하우징케어</h3>
            </div>
            <div class="bottom">
                <div class="links">
                    <a href="/privacyPolicy" class="link">개인정보처리방침</a>
                    <span class="deco">|</span>
                    <a href="/intro" class="link">회사소개</a>
                    <span class="deco">|</span>
                    <a href="/emptyPlaces" class="link pc">공실현황</a>
                    <span class="deco">|</span>
                    <a href="/asks/create" class="link">빌라위탁 문의</a>
                </div>

                <div class="infos">
                    <p class="info">
                        주소 : 서울특별시 강남구 역삼동 628 중일빌딩 1층 강남건설
                    </p>
                    <p class="info">
                        대표전화 : 02-569-1000
                    </p>
                    <p class="info">
                        이메일 : gn3678@naver.com
                    </p>
                </div>

                <p class="copyright">
                    Copyright  강남건설 · 강남하우징케어 All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</template>
<script>
import {Link} from '@inertiajs/inertia-vue';
export default {
    components: {Link},
    data(){
        return {

        }
    },
    mounted() {

    }
}
</script>
