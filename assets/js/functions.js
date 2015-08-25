jQuery(document).ready(function($){

	"use strict";


	/* Toggle Submenu Fuction
  ---------------------------------------------*/
	jQuery.fn.toggle = function( fn, fn2 ) {
		if ( !jQuery.isFunction( fn ) || !jQuery.isFunction( fn2 ) ) {
			return oldToggle.apply( this, arguments );
		}
		var args = arguments,
		guid = fn.guid || jQuery.guid++,
		i = 0,
		toggler = function( event ) {
			var lastToggle = ( jQuery._data( this, "lastToggle" + fn.guid ) || 0 ) % i;
			jQuery._data( this, "lastToggle" + fn.guid, lastToggle + 1 );
			event.preventDefault();
			return args[ lastToggle ].apply( this, arguments ) || false;
		};
		toggler.guid = guid;
		while ( i < args.length ) {
			args[ i++ ].guid = guid;
		}
		return this.click( toggler );
	};


	function erika_hover(){
    jQuery('.service-box').each(function() {
        var iconheight = jQuery(this).find('.service-icon').outerHeight();
        jQuery(this).hover(function() {
            jQuery(this).find('.service-icon').stop().animate({'margin-top' : iconheight*-1-10, 'opacity' : 0}, 400, 'jswing');
        }, function(){
            jQuery(this).find('.service-icon').stop().animate({'margin-top' : 0, 'opacity' : 1}, 200, 'jswing');
        });
    });

		jQuery.slidebars();
	};

	jQuery(window).load(function(){
		var resizeTimer;
		jQuery(window).resize(function() {
		  clearTimeout(resizeTimer);
		  resizeTimer = setTimeout(erika_hover, 0);
		}).resize();
	});

  // Eqal Height
  function equalHeight(group) {
      var tallest = 0;
      group.each(function() {
          var thisHeight = jQuery(this).height();
          if(thisHeight > tallest) {
              tallest = thisHeight;
          }
      });
      group.height(tallest);
  }

  equalHeight(jQuery('.service-box'));


	/* Responsive Mobile Menu
  ---------------------------------------------*/
	jQuery('#header .logo-header').append('<div class="visible-xs visible-sm mobile-menu-action pull-right sb-toggle-right navbar-right"><i class="fa fa-bars"></i></div>');

	var navlist = jQuery('.site-menu-container > nav ul').clone();
	var submenu = '<span class="submenu"><i class="fa fa-angle-double-down"></i></span>';
	navlist.removeClass().addClass('mobile-menu');

	navlist.find('ul').removeAttr('style');
	navlist.find('li:has(> ul) > a').after(submenu);;
	navlist.find('.submenu').toggle(function(){
		jQuery(this).parent().addClass('over').find('>ul').slideDown(200);
	},function(){
		jQuery(this).parent().removeClass('over').find('>ul').slideUp(200);
	});
	jQuery('.sb-slidebar .sb-menu-trigger').after(navlist[0]);

	// Affix
	jQuery('.header-fixed').affix({
		offset: {
			top: 220,
			bottom: function () {
				return (this.bottom = jQuery('#footer').outerHeight(true))
			}
		}
	});


	/* Init animation
  ---------------------------------------------*/
	var wow = new WOW(
		{
			boxClass:     'wow',
			animateClass: 'animated',
			offset:       0,
			mobile:       false
		}
	);
	wow.init();


	/* header social
  ---------------------------------------------*/
  // jQuery('.social-info').each(function(){
  //     jQuery(this).find('li').hover(function() {
  //         jQuery('.social-info').find('li.active').removeClass('active');
  //         jQuery(this).addClass('active');
  //     });
  // });

	/* scroll back to top
  ---------------------------------------------*/
	(function($){$.fn.backToTop=function(options){var $this=$(this);$this.hide().click(function(){$("body, html").animate({scrollTop:"0px"});});var $window=$(window);$window.scroll(function(){if($window.scrollTop()>0){$this.fadeIn();}else{$this.fadeOut();}});return this;};})(jQuery);
	// adding back to top button
	jQuery('body').append('<a class="back-to-top"><i class="fa fa-angle-up"></i></a>');
	jQuery('.back-to-top').backToTop();

});