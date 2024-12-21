<template>
    <ul class="right">
        <li class="col-group" v-for="(data, index) in years.reverse()" :key="index">
            <div class="year">
                <p class="g-sans">
                    {{data[0]}}
                </p>
            </div>
            <ul class="txt-box">
                <li v-for="(history,index) in data[1]" :key="index">
                    <span class="date">{{history.data.date}}</span>
                    <span class="con">{{ history.data.title }}</span>
                </li>
            </ul>
        </li>
    </ul>

</template>
<script>

export default {
    props: ["item"],
    data(){
        return {

        }
    },

    methods: {

    },

    computed: {
        years(){
            return Object.entries(this.item);
        }
    },

    mounted() {
    }
}
</script>
