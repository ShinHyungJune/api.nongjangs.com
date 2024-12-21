<template>
    <div class="sub-title col-group">
        <h2>
            {{activeSubgnb}}
        </h2>
        <ul class="col-group">
            <li>
                <Link href="/">
                    <i class="xi-home"></i>
                    HOME
                </Link>
            </li>
            <li>
                <i class="xi-angle-right-min"></i>
            </li>
            <li>
                <a href="#">
                    {{ activeGnb }}
                </a>
            </li>
            <li>
                <i class="xi-angle-right-min"></i>
            </li>
            <li>
                <a href="#">
                    {{ activeSubgnb }}
                </a>
            </li>
        </ul>
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
    }
}
</script>
