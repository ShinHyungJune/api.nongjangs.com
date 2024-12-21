<template>
    <header class="header">
        <a href="/" class="logo">
            <img src="/images/logo.png" alt="">
        </a>

        <button class="btn-sidebar">
            <i class="xi-bars inactive"></i>
            <i class="xi-close active"></i>
        </button>

        <div class="sidebar">
            <a href="/" class="sidebar-logo">
                <img src="/images/logo-w.png" alt="">
            </a>

            <div class="gnbs-wrap">
                <div class="gnbs">
                    <a href="/about" class="gnb">회사소개</a>
                    <div class="sub-gnbs">
                        <a href="/about" class="sub-gnb">기업개요</a>
                        <a href="/intro" class="sub-gnb">위탁소개</a>
                        <a href="/histories" class="sub-gnb">연혁</a>
                    </div>
                </div>

                <div class="gnbs">
                    <a href="/vrs" class="gnb">건설 · 인테리어</a>
                    <div class="sub-gnbs">
                        <a href="/vrs" class="sub-gnb">VR 체험관</a>
                        <a href="/architectures" class="sub-gnb">아키텍쳐</a>
                    </div>
                </div>

                <div class="gnbs">
                    <a href="/emptyPlaces" class="gnb">임대 · 위탁</a>
                    <div class="sub-gnbs">
                        <a href="/emptyPlaces" class="sub-gnb">공실현황</a>
                        <a href="/villas" class="sub-gnb">관리빌라</a>
                    </div>
                </div>

                <div class="gnbs">
                    <a href="/notices" class="gnb">세입자 문의센터</a>
                    <div class="sub-gnbs">
                        <a href="/notices" class="sub-gnb">공지사항</a>
                        <a href="/afterServices" class="sub-gnb">AS 접수</a>
                        <a href="/qnas" class="sub-gnb">문의하기</a>
                    </div>
                </div>

                <div class="gnbs">
                    <a href="/asks/create" class="gnb" style="border-bottom:none;">빌라위탁 문의</a>
                </div>

                <div class="gnbs">
                    <a href="/documents" class="gnb">자료실</a>
                </div>
            </div>
        </div>
    </header>

</template>
<script>
import {Link} from '@inertiajs/inertia-vue';
import {Inertia} from '@inertiajs/inertia';
export default {
    components: {Link},
    data() {
        return {
            user: this.$page.props.user,
            form: this.$inertia.form({}),
            logo: this.$page.props.logo ? this.$page.props.logo.data : "",
        }
    },
    methods: {
        move(link){
            /*if($(window).width() > 800){
                this.form.get(link);
            }*/
        },
        init(){
            $(".header .btn-sidebar").off();

            if($(window).width() < 768){
                $(".header .btn-sidebar").click(function(){
                    $(".header").toggleClass("active");
                    $(this).toggleClass("active");

                    if($(".header").hasClass("active"))
                        return $("html,body").css("overflow", "hidden");

                    return $("html,body").css("overflow", "auto");
                });
            }else{
                $(".header .btn-sidebar").not(".active").hover(function(){
                    // $("html,body").css("overflow", "hidden");

                    return $(".header").addClass("active");
                }, function(){});

                $(".header .btn-sidebar").click(function(){
                    // $("html,body").css("overflow", "auto");

                    $(".header").removeClass("active");
                    return $(this).removeClass("active");
                });
            }


        }
    },
    mounted() {
        this.init();

        Inertia.on('start', (event) => {
            this.init();
        });

    }
}
</script>
