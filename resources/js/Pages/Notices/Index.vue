<template>
    <div class="area-sub area-boards area-inquiry">
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
            <div class="contents">
                <a href="/logout" class="btn-login m-btn type01" v-if="$page.props.user">
                    <i class="xi-log-out"></i>
                    <span class="text">로그아웃</span>
                </a>
                <a href="/login" class="btn-login m-btn type01" v-else>
                    <i class="xi-log-in"></i>
                    <span class="text">로그인</span>
                </a>

                <div class="top">
                    <p class="body">{{ user.address }} {{user.address_detail}}</p>
                    <h3 class="title">{{ user.villa }}</h3>
                    <div class="infos">
                        <div class="info">
                            <i class="xi-user"></i>
                            <h3 class="title">
                                <span class="text">세</span>
                                <span class="text">입</span>
                                <span class="text">자</span>
                                <span class="text">명</span>
                            </h3>
                            <p class="body">{{ user.name }}</p>
                        </div>
                        <div class="info">
                            <i class="xi-call"></i>
                            <h3 class="title">
                                <span class="text">연</span>
                                <span class="text">락</span>
                                <span class="text">처</span>
                            </h3>
                            <p class="body">{{ user.contact }}</p>
                        </div>
                    </div>

                    <div class="pd-48"></div>

                    <div class="m-tabs type02">
                        <a href="/notices" class="m-tabs-tab active">공지사항</a>
                        <a href="/afterServices" class="m-tabs-tab">AS 센터</a>
                        <a href="/qnas" class="m-tabs-tab">문의하기</a>
                    </div>

                    <div class="pd-48"></div>

                    <div class="table-top">
                        <h3 class="title">공지사항</h3>
                    </div>

                    <table class="m-table type01">
                        <colgroup>
                            <col style="width:70%">
                            <col style="width:30%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="title">제목</th>
                            <th class="pc">작성일</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="items.data.length === 0">
                            <td colspan="2" class="m-empty type01">데이터가 없습니다.</td>
                        </tr>
                        <tr :class="item.important ? 'active' : ''" v-for="item in items.data" :key="item.id" @click="$inertia.get(`/notices/${item.id}`)">
                            <td class="title">{{item.title}}</td>
                            <td class="pc">{{item.created_at}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <pagination :meta="items.meta" @paginate="(page) => {form.page = page; filter()}" />

            </div>


            <div class="pd-120"></div>
        </div>
    </div>
</template>

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
