<template>
    <div class="area">
        <h3 class="title">사전가입내역</h3>
        <div class="btns">
            <a href="/users/export" class="btn">사전가입내역 다운로드</a>
        </div>
        <table>
            <thead>
            <tr>
                <th>이메일</th>
                <th>닉네임</th>
                <th>연락처</th>
                <th>이메일 수신동의</th>
                <th>SNS 수신동의</th>
                <th>인스타그램</th>
                <th>블로그</th>
                <th>유튜브</th>
                <th>등록일</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in items.data" :key="item.id">
                <td>{{item.email}}</td>
                <td>{{item.nickname}}</td>
                <td>{{item.contact}}</td>
                <td>{{item.agree_promotion_email ? 'Y' : 'N'}}</td>
                <td>{{item.agree_promotion_sms ? 'Y' : 'N'}}</td>
                <td>{{item.instargram }}</td>
                <td>{{item.blog }}</td>
                <td>{{item.youtube }}</td>
                <td>{{item.created_at}}</td>
            </tr>
            </tbody>
        </table>

        <pagination :meta="items.meta" @paginate="(page) => {form.page = page; filter()}" />

    </div>
</template>
<style>

</style>
<script>
import {Link} from '@inertiajs/inertia-vue';
import Subgnbs from "../../Components/Subgnbs";
import Navigation from "../../Components/Navigation";
import Pagination from "../../Components/Pagination";

export default {
    components: {Pagination, Navigation, Subgnbs, Link},

    data() {
        return {
            user: this.$page.props.user.data,
            items: this.$page.props.items,
            form: this.$inertia.form({
                page: 1,
                word: this.$page.props.word,
            }),
        }
    },

    methods: {
        filter(){
            this.form.get("/notices", {
                preserveScroll: true,
                preserveState: true,
                replace: true,
                onSuccess: (page) => {
                    this.items = page.props.items;
                }
            });
        },
    },

    mounted() {
    }
}
</script>
