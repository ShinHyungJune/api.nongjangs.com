<template>
    <nav :class="`his-wrap his-${item[0]} col-group`" :data-link="item[0]">
        <div class="left">
            <p class="g-sans-bold">
                {{ item[0] }}'s
            </p>
        </div>
        <ul class="right">
            <year :item="item[1]" />
        </ul>
    </nav>
</template>
<script>

import Year from "./Year";
export default {
    components: {Year},
    props: ["item"],
    data(){
        return {

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
