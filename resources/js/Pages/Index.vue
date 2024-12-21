<template>
    <div class="area-main">
        <div class="pop-parent" id="popParent" v-if="activePop && pops.data.length > 0">
            <div class="pop">
                <div class="swiper">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" v-for="pop in pops.data" :key="pop.id">
                                <div class="m-ratioBox-wrap">
                                    <div class="m-ratioBox">
                                        <img :src="pop.img ? pop.img.url : ''">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-control">
                            <div class="swiper-pagination"></div>

                            <button class="swiper-btn-control">
                                <div class="icon_stop" title="슬라이더 자동재생 멈춤">
                                    <span class="stick stick01"></span>
                                    <span class="stick stick02"></span>
                                </div>

                                <div class="icon_play" title="슬라이더 자동재생 시작"></div>
                            </button>
                        </div>
                    </div>

                    <div class="pop-btns">
                        <!-- <a href="#a" id="popupToday" class="btnDivPopClose"><span class="icon"></span> 오늘하루 보지 않기</a> -->
                        <input type="checkbox" name="oneday" id="oneday_check" @click="closeToday()">
                        <label for="oneday_check" @click="closeToday()">
                            <span class="icon"></span> 오늘하루 보지 않기
                        </label>


                        <!-- <input type="checkbox" id="popupToday"> <label for="popupToday">오늘하루 열지않음</label> -->
                        <button class="btn-toggle">
                            <img src="/images/arrowSmallRight-white.png" alt="">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" target="_blank" class="btn-vr">VR TOUR <i class="xi-arrow-right"></i></a>

        <div class="banner-swiper swiper">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" :style="`background:url(${bannerUrl(banner)}) no-repeat; background-size:cover;`" v-for="banner in banners.data" :key="banner.id"></div>
                </div>
            </div>

            <div class="swiper-btns">
                <div class="swiper-btn swiper-btn-prev">
                    <i class="xi-angle-left"></i>
                </div>
                <div class="swiper-btn swiper-btn-next">
                    <i class="xi-angle-right"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue';

export default {
    components: {Link},

    data() {
        return {
            pops: this.$page.props.pops ? this.$page.props.pops : "",
            activePop: true,

            banners: this.$page.props.banners,
        }
    },

    methods: {
        closeToday() {
            window.localStorage.setItem("closed_at", (new Date()).getDate());

            this.activePop = false;
        },

        closePop() {
            this.activePop = false;
        },

        bannerUrl(banner){
            let url = banner.img.url;

            if($(window).width() < 768 && banner.mobile)
                url = banner.mobile.url;

            return url;
        },
    },

    computed: {

    },

    mounted() {
        let self = this;

        let closedAt = window.localStorage.getItem("closed_at");

        if(closedAt && closedAt >= (new Date()).getDate())
            this.activePop = false;

        $(".pop-parent .btn-toggle").click(function () {
            $(".pop-parent").toggleClass("active");
        });

        new Swiper('.pop-parent .swiper-container', {
            autoplay: {
                delay: 5000,
            },
            loop: true,
            pagination: {
                el: '.pop-parent .swiper-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.pop-parent .swiper-btn-next',
                prevEl: '.pop-parent .swiper-btn-prev',
            },
            breakpoints: {}
        });

        new Swiper(".banner-swiper .swiper-container", {
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            loop:true,
            navigation: {
                nextEl: ".banner-swiper .swiper-btn-next",
                prevEl: ".banner-swiper .swiper-btn-prev",
            },
            on: {
                "sliceChange" : function(){
                    $(".btn-vr").attr("href", self.banners.data[this.realIndex].url);
                }
            }
        });

        if(this.banners.data.length > 0)
            $(".btn-vr").attr("href", this.banners.data[0].url);

        $(".swiper-slide").css("height", $(window).height());

    }
}
</script>
