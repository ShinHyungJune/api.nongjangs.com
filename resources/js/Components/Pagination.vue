<template>

    <ul class="pagination">
        <li @click="prev"><a href="" @click.prevent=""><i class="xi-angle-left-thin"></i></a></li>

        <li :class="pageClass(page)" v-for="page in pages" :key="page" @click="paginate(page)"><a href="" @click.prevent="">{{page}}</a></li>

        <li @click="next"><a href="" @click.prevent=""><i class="xi-angle-right-thin"></i></a></li>
    </ul>
</template>
<script>
export default {
    props:["meta"],

    computed: {
        pages(){
            let i = 1;
            let pages = [];

            for(i = 1; i <= this.meta.last_page; i++){
                pages.push(i);
            }

            return pages;
        }
    },

    methods: {
        pageClass(page){
            return this.meta.current_page == page
                ? "now"
                : "";
        },

        paginate(page){
            this.$emit("paginate", page);

            document.querySelector("html").scrollTop = 0;
        },

        first(){
            this.paginate(1);
        },

        prev(){
            if(this.meta.current_page > 1)
                this.paginate(parseInt(this.meta.current_page) - 1);
        },

        next(){
            if(this.meta.current_page < this.meta.last_page)
                this.paginate(parseInt(this.meta.current_page) + 1);
        },

        last(){
            this.paginate(this.meta.last_page);
        }
    }

}
</script>
