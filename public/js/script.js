$(document).ready(function(){
    $('#header').load('/components/header.html');
    $('#pagination').load('/components/pagination.html');
    $('#footer').load('/components/footer.html');
    $('#top_menu').load('/components/top_menu.html');
    $('#mp_nav').load('/components/mp_nav.html');
    $('#mb_nav').load('/components/mb_nav.html');

    //header - search
    $('#header .search-btn').click(function(){
        $('#search').addClass('active');
    });

    //modal 닫힘
    $('.modal-container .close-btn').click(function () {
        $('.modal-container').removeClass('active');
    });

    $('.modal-container').click(function (e) {
        if (e.target === this) {
            $(".modal-container").removeClass('active');
        }
    });

    //toggle content
    $('.toggle-title').click(function(){
        $(this).parent().toggleClass('toggle-active');
    });

    
})