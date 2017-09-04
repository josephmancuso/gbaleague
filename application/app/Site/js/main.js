(function ($) {
    "use strict";
    /*--
        Preloader
    ------------------------*/
    var win = $(window);
    win.on('load', function() {
        $('.preloader').fadeOut('slow');;
    });
    /*--
        Menu Sticky
    -----------------------------------*/
    var windows = $(window);
    windows.on('scroll', function() {
        var sticky = $('.header-area')
        var scroll = windows.scrollTop();
        if (scroll < 400) {
            sticky.removeClass('stick');
        }else{
            sticky.addClass('stick');
        }
    });
    /*--
        Mean Mobile Menu
    ------------------------*/
    $('.main-menu nav').meanmenu({
        meanScreenWidth: '750',
        meanMenuContainer: '.mobile-menu',
        meanMenuClose: '<i class="zmdi zmdi-close"></i>',
        meanMenuOpen: '<i class="zmdi zmdi-menu"></i>',
        meanRevealPosition: 'right',
        meanMenuCloseSize: '30px',
        onePage: true,
    });
    /*--
        Counter Up
    ------------------------*/
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    }); 
    /*--
        Scroll Up
    ------------------------*/
    $.scrollUp({
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade',
        scrollText: '<i class="fa fa-chevron-up"></i>',
    });
    
    $('#demo-container').mixItUp();
    
    /*--
        Scroll Up
    -----------------------------------*/
    $.scrollUp({
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade',
        scrollText: '<i class="icofont icofont-simple-up"></i>',
    });
    
    $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top - 0
        }, 1000);
        return false;
      }
    }
    });
    
    
    
    })(jQuery);	