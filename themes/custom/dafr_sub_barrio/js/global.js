/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.libraries_init = {
    attach: function (context, settings) {
    	AOS.init({
        duration: 1300,
      });

      if ($('.rellax', context).length) {
        new Rellax('.rellax', {                    
        });
      }
    },
  };


  // sticky menu 
  Drupal.behaviors.headerScroll = {
    attach: function (context, settings) {
        var $header = $('#header');
        var lastScrollTop = 0;

        $(window).on('scroll.headerScroll', function () {
            var scrollTop = $(this).scrollTop();

            if (lastScrollTop > 40 && scrollTop > lastScrollTop) {
                // Scrolling down
                // $header.addClass('transparent');
                $header.addClass('hide-up');
                // $header.removeClass('stick-fixed');
            } else {
                if (scrollTop < 40) {
                    $header.addClass('transparent top');
                } else {
                    $header.removeClass('hide-up');
                    $header.removeClass('transparent');
                    $header.removeClass('top');
                    $header.addClass('scroll-up')
                }
                // Scrolling up          
                // $header.removeClass('transparent');

                // $header.addClass('stick-fixed');         

            }

            lastScrollTop = scrollTop;
        });
    }
};


  Drupal.behaviors.dafr_sub_barrio = {
    attach: function (context, settings) {

    }
  };

})(jQuery,Drupal);
