
$(document).ready(function(){
    //헤더 푸터 컴포넌트
    $('#admin_header').load('components/admin_header.html', function(){

        $('.sitemap-btn').click(function(){
            $(this).toggleClass('active');
            $('.sub-gnb-wrap').toggleClass('sitemap');
            $('.sitemap-bg').toggleClass('active');
        });

        $('.gnb-menu').click(function(){
            $('.gnb-menu').removeClass('active');
            $(this).addClass('active');
            $('.sub-gnb-wrap').removeClass('active');
            $(this).siblings('.sub-gnb-wrap').addClass('active');
        });

        $('.toggle-btn').click(function(){
            $('html, body').addClass('fixed');
            $('.gnb-wrap').addClass('active');
            $('.gnb-wrap-bg').addClass('active');
        });

        $('.toggle-close-btn').click(function(){
            $('html, body').removeClass('fixed');
            $('.gnb-wrap').removeClass('active');
            $('.gnb-wrap-bg').removeClass('active');
        });

    });

    $('#pagination').load('components/pagination.html');

    //modal 닫힘
    $('.modal-container .close-btn').click(function () {
        $('.modal-container').hide();
    });

    $('.modal-container').click(function (e) {
        if (e.target === this) {
            $(".modal-container").hide();
        }
    });

    //table 가로 스크롤
    const tableWrap = document.querySelector('.admin-table-wrap');
    let isDragging = false;
    let startX, scrollLeft;

    tableWrap.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - tableWrap.offsetLeft;
        scrollLeft = tableWrap.scrollLeft;
    });

    tableWrap.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - tableWrap.offsetLeft;
        const walk = x - startX; // 이동 거리 계산
        tableWrap.scrollLeft = scrollLeft - walk;
    });

    tableWrap.addEventListener('mouseup', () => {
        isDragging = false;
    });

    tableWrap.addEventListener('mouseleave', () => {
        isDragging = false;
    });
})