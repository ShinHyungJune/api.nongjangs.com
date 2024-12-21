<template>
    <div id="wrap" v-if="pathname.includes('admin')">
        <div id="main" class="main">
            <div style="display:flex;">
                <div class="manager-nav" v-if="!pathname.includes('login')">
                    <img src="/images/LOGO.png" alt="" class="logo">

                    <a href="/admin/banners" :class="navClass('/admin/banners')">배너 관리</a>
                    <a href="/admin/pops" :class="navClass('/admin/pops')">팝업 관리</a>
                    <a href="/admin/histories" :class="navClass('/admin/histories')">연혁</a>
                    <a href="/admin/vrs" :class="navClass('/admin/vrs')">VR</a>
                    <a href="/admin/architectures" :class="navClass('/admin/architectures')">아키텍쳐</a>
                    <a href="/admin/emptyPlaceCategories" :class="navClass('/admin/emptyPlaceCategories')">공실 지역 관리</a>
                    <a href="/admin/emptyPlaces" :class="navClass('/admin/emptyPlaces')">공실현황</a>
                    <a href="/admin/villas" :class="navClass('/admin/villas')">관리빌라</a>
                    <a href="/admin/users" :class="navClass('/admin/users')">세입자 등록/관리</a>
                    <a href="/admin/notices" :class="navClass('/admin/notices')">세입자 공지사항</a>
                    <a href="/admin/afterServices" :class="navClass('/admin/afterServices')">세입자 AS 센터</a>
                    <a href="/admin/qnas" :class="navClass('/admin/emailVerifications')">세입자 문의</a>
                    <a href="/admin/asks" :class="navClass('/admin/asks')">빌라 위탁 문의</a>
                    <a href="/admin/documents" :class="navClass('/admin/documents')">자료실</a>

                    <div class="nav-bt">
                        COPYRIGHT 강남건설 · 강남하우징케어
                        <br/>ALL RIGHT RESERVED.
                    </div>
                </div>
                <div class="container">
                    <div class="header" v-if="!pathname.includes('login')">
                        <div class="hd-le">
                            <p style="padding-left:24px;">강남건설 관리자 모드</p>
                        </div>
                        <div class="he-ri">
                            <a href="/admin/logout" class="log-out-btn">로그아웃<i class="xi-log-out"></i></a>
                        </div>
                    </div>

                    <div class="container-inner">
                        <slot />
                    </div>
                </div>
            </div>

            <flash />
        </div>
    </div>

    <div id="wrap" v-else>
        <header-vue />

        <slot />

        <flash />

        <footer-vue/>
    </div>

</template>
<script>
import HeaderVue from "../Components/Header";
import FooterVue from "../Components/Footer";
import Flash from "../Components/Flash";
export default {
    components: {HeaderVue, FooterVue, Flash},
    data(){
        return {
            pathname: location.pathname,
        }
    },

    methods: {
        navClass(name){
            return this.pathname.includes(name) ? 'active' : '';
        }
    },

    mounted() {
        location.pathname === "/intro"
            ? $("#header, #footer").hide()
            : $("#header, #footer").show();

        this.$inertia.on("success", () => {
            this.pathname = location.pathname;

            location.pathname === "/intro"
                ? $("#header, #footer").hide()
                : $("#header, #footer").show();
        });
    }
}
</script>
