<template>
    <div>
        <button :class="`btn-toggle ${active ? 'active' : ''}`" @click="active = !active">
            <img src="/img/menu.png" alt="" class="inactive animated flash infinite">
            <img src="/img/x.png" alt="" class="active animated flash infinite">
        </button>

        <div :class="`m-dashboard-sidebar ${active ? 'active' : ''}`">
            <div class="box-top">
                <h3 class="title">안녕하세요, <span class="point">{{user.name ? user.name : '소셜로그인'}}</span>님!</h3>
                <p class="body">보유포인트 : {{ user.point.toLocaleString() }}원</p>

                <Link href="/logout" class="btn">로그아웃</Link>
            </div>

            <div class="menus-wrap">
                <div class="menus">
                    <div class="menu-wrap">
                        <h3 class="menu">설문조사</h3>
                    </div>

                    <div class="menus">
                        <div class="menu-wrap">
                            <Link href="#" class="menu">참여내역</Link>
                        </div>
                        <div class="menu-wrap">
                            <Link href="#" class="menu">포인트교환내역</Link>
                        </div>
                    </div>
                </div>

                <!--
                <div class="menus">
                    <div class="menu-wrap">
                        <h3 class="menu">관심상품</h3>
                    </div>

                    <div class="menus">
                        <div class="menu-wrap">
                            <Link href="/likeProducts" class="menu">찜한 상품</Link>
                        </div>
                        <div class="menu-wrap">
                            <Link href="/latestProducts" class="menu">최근 본 상품</Link>
                        </div>
                    </div>
                </div>
                -->

                <div class="menus">
                    <div class="menu-wrap">
                        <h3 class="menu">내 정보</h3>
                    </div>

                    <div class="menus">
                        <div class="menu-wrap">
                            <Link href="/users/edit" class="menu">내 정보 수정</Link>
                        </div>
                        <div class="menu-wrap">
                            <Link href="/qnas" class="menu">문의내역</Link>
                        </div>
                        <div class="menu-wrap">
                            <Link href="/users/remove" class="menu red">회원탈퇴</Link>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
    import {Link} from '@inertiajs/inertia-vue';
    export default {
        components: {Link},
        data(){
            return {
                user: this.$page.props.user.data,
                active: false
            }
        },
        methods: {
            ready(){
                alert("준비중입니다.");
            }
        }
    }
</script>
