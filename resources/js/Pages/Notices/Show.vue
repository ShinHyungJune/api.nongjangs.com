<template>
    <div class="area-sub area-detail details-page">
        <div class="wrap">
            <div class="title-wrap">
                <h1>{{ item.title }}</h1>
                <p>{{ item.address }}</p>
            </div>

            <div class="editor-wrap toastui-editor-contents" v-html="item.description"></div>

            <input-files :default="item.files" :disabled="true" />

            <div class="list-turn">
                <a :href="`/notices/${item.prev.id}`" class="page-btn" v-if="item.prev">
                    <div>
                        <i class="xi-long-arrow-left pc"></i>
                        <span>이전글</span>
                        <i class="xi-angle-up-min mb"></i>
                    </div>
                    <p>{{ item.prev.title }}</p>
                </a>
                <a href="/notices" class="btn-st1">목록</a>
                <a :href="`/notices/${item.next.id}`" class="page-btn" v-if="item.next">
                    <div>
                        <span>다음글</span>
                        <i class="xi-long-arrow-right pc"></i>
                        <i class="xi-angle-down-min mb"></i>
                    </div>
                    <p>{{ item.next.title }}</p>
                </a>
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
import InputFiles from "../../Components/Form/InputFiles";

export default {
    components: {InputFiles, Pagination, Navigation, Subgnbs, Link},

    data() {
        return {
            item: this.$page.props.item.data,

        }
    },

    methods: {

    },

    mounted() {
    }
}
</script>
