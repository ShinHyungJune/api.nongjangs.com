<template>
    <div class="area-sub area-login">
        <div class="visual" style="background:url('/images/sub-top-04.jpg') no-repeat; background-size:cover; background-position:center;">
            <h3 class="title">문의하기</h3>
        </div>

        <div class="page-info">
            <p class="subtitle">세입자 문의센터</p>

            <h3 class="title">TENANT INQUIRY</h3>

            <div class="navs">
                <div class="nav"><i class="xi-home"></i></div>
                <i class="deco xi-angle-right-min"></i>
                <div class="nav">문의하기</div>
                <i class="deco xi-angle-right-min"></i>
                <div class="nav">세입자 문의센터</div>
            </div>
        </div>

        <div class="wrap">
            <form action="" @submit.prevent="login">
                <div class="top">
                    <img src="/images/logo-login.png" alt="" class="logo">

                    <h3 class="title">Log in</h3>

                    <p class="body">
                        강남건설에서 관리하는 전/월세 세입자의 문의센터입니다.
                        <br/>발급받은 아이디와 비밀번호를 입력해 주세요.
                    </p>
                </div>

                <div class="m-input-text type01">
                    <input type="text" placeholder="아이디를 입력해 주세요." v-model="form.ids">
                    <p class="m-input-error">{{ form.errors.ids }}</p>
                </div>

                <div class="pd-16"></div>

                <div class="m-input-text type01">
                    <input type="password" placeholder="비밀번호를 입력해 주세요." v-model="form.password">
                    <p class="m-input-error">{{ form.errors.password }}</p>
                </div>

                <div class="pd-16"></div>

                <button class="btn">로그인</button>
            </form>

            <div class="pd-120"></div>
        </div>
    </div>
</template>
<script>

import {Link} from "@inertiajs/inertia-vue";

export default {
    components: { Link},
    data(){
        return {
            form: this.$inertia.form({
                ids:"",
                password: ""
            })
        }
    },

    methods: {
        login() {
            this.form.post("/login", {
                preserveState: true,
                preserveScroll:true,
            })
        },

        mounted(){

        }
    }
}
</script>
