$(document).ready(function () {
    var li_a = $('#documenter_nav li a');
    var nav = $('#documenter_nav');
    var doc_li = $('#documenter_content section');
    $(window).on('scroll', function () {
        var cur_pos = $(this).scrollTop();
        var current = $(this);
        var parent = current.parent().parent();
        var next = current.next();
        var hasSub = next.is('ul');
        var isSub = !parent.is('#documenter_nav');
        doc_li.each(function () {
            var top = $(this).offset().top,
                    bottom = top + $(this).outerHeight();
            if (cur_pos >= top && cur_pos <= bottom) {
                nav.find('ul').each(function () {
                    if ($(this).is(":visible"))
                    {
                        // $(this).stop().slideUp();
                    }
                });
                nav.find('a[href="#' + $(this).attr('id') + '"]').next().slideDown('fast');
            }
        });
    });
    
    $('a.scroll-a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        var target = this.hash;
        var $target = $(target);
        var current = $(this);
        var parent = current.parent().parent();
        var next = current.next();
        var hasSub = next.is('ul');
        var isSub = !parent.is('#documenter_nav');
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
        if (isSub) {
            parent.stop().slideDown('fast');
        } else if (hasSub) {
            next.stop().slideDown('fast');
        }
    });
    
    $('#sidebar-btn').click(function () {
        $('#documenter_sidebar').toggleClass('active_menu');
        $('#document-content-main').toggleClass('active_content');
        $('#sidebar-btn').toggleClass('act-sidebar');
        $('#content-header').toggleClass('active-header');
        $('#top-header').toggleClass('active-top');
    });
    
    $('#tog-tab').click(function () {
        $('#tog-content').toggleClass('tog-content');
    });
    $('#tog-tab1').click(function () {
        $('#tog-content1').toggleClass('tog-content');
    });
    $('#tog-tab2').click(function () {
        $('#tog-content2').toggleClass('tog-content');
    });
    $('#tog-tab3').click(function () {
        $('#tog-content3').toggleClass('tog-content');
    });
    $('#tog-tab4').click(function () {
        $('#tog-content4').toggleClass('tog-content');
    });
    $('#tog-tab5').click(function () {
        $('#tog-content5').toggleClass('tog-content');
    });
    $('#tog-tab6').click(function () {
        $('#tog-content6').toggleClass('tog-content');
    });
    
//    $('body').scrollspy({target: '#documenter_nav_cover'});
    
    $('#documenter_nav').perfectScrollbar();
    
});