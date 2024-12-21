<template>
    <div :class="active ? 'm-board active' : 'm-board'" @click="active = !active">
        <div class="head">
            <div class="left">
                <p class="date">{{ qna.created_at }}</p>

                <h3 class="title">
                    <span class="state answered" v-if="qna.answer">답변완료</span>
                    <span class="state" v-else>답변대기</span>
                    {{ qna.title }}
                </h3>
            </div>

            <div class="icon">
                <img src="/img/arrowDown-white.png" alt="" class="active">
                <img src="/img/arrowDown-gray.png" alt="" class="inactive">
            </div>
        </div>

        <div class="content" v-html="qna.question"></div>
        <div class="content" v-html="qna.answer" v-if="qna.answer"></div>
    </div>
</template>
<script>

export default {
    props: ["qna"],
    data(){
        return {
            active: false
        }
    },

    methods: {

    },

    computed: {

    },

    mounted() {

    }
}
</script>
