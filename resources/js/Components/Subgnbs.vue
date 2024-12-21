<template>
    <div class="sub-top-map">
        <div class="container col-group">
            <a href="index.html" class="home-btn">
                <i class="xi-home"></i>
            </a>
            <ul class="sub-menu-list sub-menu-list1">
                <li>
                    <p class="show">
                        {{ activeGnb }}
                        <i class="xi-angle-down"></i>
                    </p>
                    <ul class="sub-menu-select">
                        <li>
                            <Link href="/introduceChurch/about" data-gnb="/introduceChurch">
                                교회소개
                            </Link>
                        </li>
                        <li>
                            <Link href="/worship/sundaySermons" data-gnb="/worship">
                                예배와 말씀
                            </Link>
                        </li>
                        <li>
                            <Link href="/openMinistry/dvDay" data-gnb="/openMinistry">
                                열린사역
                            </Link>
                        </li>
                        <li>
                            <Link href="/school/school" data-gnb="/school">
                                주일학교
                            </Link>
                        </li>
                        <li>
                            <Link href="/mission/mission" data-gnb="/mission">
                                선교
                            </Link>
                        </li>
                        <li>
                            <Link href="/community/churchLetters" data-gnb="/community">
                                커뮤니티
                            </Link>
                        </li>
                        <li>
                            <Link href="/login" data-gnb="/login">
                                회원메뉴
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="sub-menu-list sub-menu-list2">
                <li>
                    <p class="show">
                        {{ activeSubgnb }}
                        <i class="xi-angle-down"></i>
                    </p>

                    <!-- 교회소개 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('introduceChurch')">
                        <li>
                            <Link href="/introduceChurch/about">
                                담임목사 인사말
                            </Link>
                        </li>
                        <li>
                            <Link href="/introduceChurch/vision">
                                교회비전
                            </Link>
                        </li>
                        <li>
                            <Link href="/introduceChurch/promotions">
                                홍보영상
                            </Link>
                        </li>
                        <li>
                            <Link href="/introduceChurch/histories">
                                교회연혁
                            </Link>
                        </li>
                        <li>
                            <Link href="/introduceChurch/personCategories">
                                섬기는 사람들
                            </Link>
                        </li>
                        <li>
                            <Link href="/introduceChurch/guides">
                                예배안내
                            </Link>
                        </li>
                        <li>
                            <Link href="/introduceChurch/directions">
                                오시는 길
                            </Link>
                        </li>
                    </ul>

                    <!-- 예배와 말씀 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('worship')">
                        <li>
                            <Link href="/worship/sundaySermons">
                                주일설교
                            </Link>
                        </li>
                        <li>
                            <Link href="/worship/blesses">
                                찬양과 경배
                            </Link>
                        </li>
                        <li>
                            <Link href="/worship/weeklyPrayers">
                                금주의 주보
                            </Link>
                        </li>
                    </ul>

                    <!-- 열린사역 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('openMinistry')">
                        <li>
                            <Link href="/openMinistry/dvDay">
                                D.V day
                            </Link>
                        </li>
                        <li>
                            <Link href="/openMinistry/upbringing">
                                양육과정
                            </Link>
                        </li>
                        <li>
                            <Link href="/openMinistry/forums">
                                열린포럼
                            </Link>
                        </li>
                        <li>
                            <Link href="/openMinistry/prayer">
                                기도회
                            </Link>
                        </li>
                        <li>
                            <Link href="/openMinistry/ministries">
                                담임목사 동정
                            </Link>
                        </li>
                    </ul>

                    <!-- 주일학교 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('school')">
                        <li>
                            <Link href="/school/school">
                                주일학교
                            </Link>
                        </li>
                    </ul>

                    <!-- 선교 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('mission')">
                        <li>
                            <Link href="/mission/mission">
                                국내/해외선교
                            </Link>
                        </li>
                    </ul>

                    <!-- 커뮤니티 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('community')">
                        <li>
                            <Link href="/community/churchLetters">
                                교회소식
                            </Link>
                        </li>
                        <li>
                            <Link href="/community/members">
                                새가족소개
                            </Link>
                        </li>
                        <li>
                            <Link href="/community/albums">
                                교회앨범
                            </Link>
                        </li>
                        <li>
                            <Link href="/community/changingLifes">
                                변화된 삶
                            </Link>
                        </li>
                    </ul>

                    <!-- 회원메뉴 -->
                    <ul class="sub-menu-select" v-if="pathname.includes('login')">
                        <li>
                            <Link href="/login">
                                로그인
                            </Link>
                        </li>
                        <li>
                            <Link href="/user/login/privacyPolicy">
                                개인정보처리방침
                            </Link>
                        </li>
                        <li>
                            <Link href="/user/login/emailPolicy">
                                이메일무단수집거부
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
import {Link} from '@inertiajs/inertia-vue';
import {Inertia} from '@inertiajs/inertia';
export default {
    components: {Link},
    data() {
        return {
            activeGnb: "",
            activeSubgnb: ""
        }
    },
    methods: {

    },

    computed:{
        pathname(){
            return window.location.pathname;
        }
    },

    mounted() {
        let href;
        let self = this;

        $(".sub-menu-list1 li").each(function(index, item){
            href = $(item).find("a").attr("data-gnb");

            if(self.pathname.includes(href)){
                self.activeGnb = $(item).find("a").text();
            }
        });

        $(".sub-menu-list2 li").each(function(index, item){
            href = $(item).find("a").attr("href");

            if(self.pathname.includes(href)){
                self.activeSubgnb = $(item).find("a").text();
            }
        });

        //서브메뉴 선택
        $(".sub-top-map .sub-menu-list li .show").click(function(){
            $(this).siblings('.sub-menu-select').stop().slideToggle(200);
            $(this).parent('li').toggleClass('active');
        });
    }
}
</script>
