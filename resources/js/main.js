$('document').ready(function(){

    // mobile navigation (responsive)
    $('.mobile-menu').on('click', function(){
        $(this).toggleClass('active');
        $('.header-nav, .overlay').toggleClass('active');
        $('.search-btn, .search-box').removeClass("active");
        if($(window).width() < '768') {
            $('body').addClass('active');
        }
    });

    //search form (responsive)
    $('.search-btn').on('click', function(){
        $(this).toggleClass('active');
        $('.search-box').toggleClass('active');
        $('.mobile-menu, .header-nav').removeClass("active");
    });

    //overlay
    $('.overlay').on("click", function(){
        $(this).removeClass('active');
        $('body, .search-box, .mobile-menu, .header-nav').removeClass("active");
    });

    //resize function
    $(window).on('resize', function(e) {
        if($(window).width() < '768') {
            if($('.header-nav').hasClass('active') || $('.search-box').hasClass('active') ) {
                $('body').addClass('active');
            }
        } else {
            $('body').removeClass('active');
        }
    });

    // post slider - home page
    $('#post-slider').slick({
        slidesToShow: 3,
        arrows: true,
        dots: false,
        autoplay: true,
        autoplaySpeed: 7000,
        responsive: [
            {
                breakpoint: 1080,
                settings: {
                    slidesToShow: 2,
                    centerMode: true,
                    centerPadding: '40px'
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '40px'
                }
            },
        ]
    });

    //cut text (...) - would be better to remake it via php
    $(".cutText .post-excerpt").text(function(i, text) {
        if (text.length >= 245) {
            text = text.substring(0, 245);
            let lastIndex = text.lastIndexOf(" ");       // позиция последнего пробела
            text = text.substring(0, lastIndex) + '...'; // обрезаем до последнего слова
        }
        $(this).text(text);
    });

    // load more button - would be better to remake it via php
    let loadMorePost = $(".loadMore .tab-content_block.current .tab-content_item");
    loadMorePost.slice(0, 5).show();

    $("body").on('click touchstart', '.load-more_btn', function (e) {
        e.preventDefault();
        $(".loadMore .tab-content_block.current .tab-content_item:hidden").slice(0, 1).slideDown();
        if ($(".loadMore .tab-content_block.current .tab-content_item:hidden").length == 0) {
            $(".load-more").css('visibility', 'hidden');
        }
        $('html,body').animate({
            scrollTop: $(this).offset().top - 300
        }, 500);
    });

    // tabs
    $('.tab-link').click(function(){
        let tab_id = $(this).attr('data-tab');

        $('.tab-link').removeClass('current');
        $('.tab-content_block').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    });

});
