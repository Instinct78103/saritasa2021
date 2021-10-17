jQuery.event.special.touchstart = {
  setup: function (_, ns, handle) {
    this.addEventListener('touchstart', handle, {passive: !ns.includes('noPreventDefault')});
  },
};
jQuery.event.special.touchmove = {
  setup: function (_, ns, handle) {
    this.addEventListener('touchmove', handle, {passive: !ns.includes('noPreventDefault')});
  },
};
jQuery.event.special.wheel = {
  setup: function (_, ns, handle) {
    this.addEventListener('wheel', handle, {passive: true});
  },
};
jQuery.event.special.mousewheel = {
  setup: function (_, ns, handle) {
    this.addEventListener('mousewheel', handle, {passive: true});
  },
};

/**
 * @typedef {Object} jQuery
 */

(function ($) {

  /**
   * Smooth scrolling
   */
  function scrollToElem(elem) {
    if ($(elem).length) {
      $([document.documentElement, document.body]).animate({
        scrollTop: $(elem).offset().top - 115,
      }, 800);
    }
  }

  /**
   * Open mobile menu
   */
  function openMobileMenu() {
    $('.ocm-effect-wrap, #slide-out-widget-area, .mobile-menu-toggle, .slide_out_area_close').addClass('open');
    $('#ajax-content-wrap').css({'position': 'relative', 'top': '-' + $(window).scrollTop() + 'px'});
    $('.ocm-effect-wrap').css({'height': ''}).css({'height': window.innerHeight}); //First, we reset the previous value
  }

  /**
   * Close mobile menu
   */
  function closeMobileMenu() {
    $('.ocm-effect-wrap, #slide-out-widget-area, .mobile-menu-toggle, .slide_out_area_close').removeClass('open');
    setTimeout(function () {
      $('.ocm-effect-wrap').css({'height': ''});
      $(window).scrollTop(Math.abs(parseInt($('#ajax-content-wrap').css('top'))));
      $('#ajax-content-wrap').css({'position': '', 'top': ''});
    }, 700);
  }

  /**
   * Due to the header `position: fixed` needs to add another `div#header-space`.
   * Otherwise, the content below will be hidden under the header. This value changes on screen resizing.
   * See `The values depending on window resizing` below
   */
  $(function () {
    $('.ocm-effect-wrap > #header-space').css({'height': $('#header-outer').height()});
    $('.ocm-effect-wrap > #ajax-content-wrap').css({'padding-top': $('#header-outer').height()});
  });

  /**
   * The values depending on window resizing
   */
  $(window).on('resize', function () {
    $('.ocm-effect-wrap.open').css({'height': this.innerHeight});
    $('.ocm-effect-wrap > #header-space').css({'height': $('#header-outer').height()});
    setTimeout(() => {
      $('.ocm-effect-wrap > #ajax-content-wrap').css({'padding-top': $('#header-outer').height()});
    }, 200);
  });

  $(window).on('scroll', function () {
    if ($(window).scrollTop() > 150) {
      $('#logo > img').css({'height': '30px'});
    } else {
      $('#logo > img').css({'height': '45px'});
    }
  });

  document.querySelector('.mobile-menu-toggle').addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    if ($('.ocm-effect-wrap').hasClass('open')) {
      closeMobileMenu();
    } else {
      openMobileMenu();
    }
  });

  /**
   * Expand submenu when the mobile menu is already open
   */
  document.querySelectorAll('#slide-out-widget-area .menu > li.menu-item-has-children')
    .forEach(item => item.addEventListener('click', function (e) {
      if (e.target.matches('a[href="#"]')) {
        e.preventDefault();
      }

      $('#slide-out-widget-area .menu > li.menu-item-has-children').not($(this)).removeClass('open-submenu');
      $(this).toggleClass('open-submenu');
    }));

  $('header .menu-item-has-children').on('click', e => {
    if (e.target.matches('a[href="#"]')) {
      e.preventDefault();
    }
  });

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      scrollToElem(this.getAttribute('href'));
    });
  });

  /**
   * Popup form
   */
  document.addEventListener('click', e => {
    if (e.target.matches('.ocm-effect-wrap.open *')
      || e.target.matches('.slide_out_area_close.open *')) {
      e.preventDefault();
      e.stopPropagation();
      closeMobileMenu();
    } else if (e.target.matches('.ocm-effect-wrap:not(.open) .custom-menu-item-email')
      || e.target.matches('.ocm-effect-wrap:not(.open) .custom-menu-item-email *')
      || e.target.matches('.cf-init button')) {
      if (document.querySelector('.sar-popup-overlay')) {
        e.preventDefault();
        e.stopPropagation();
        document.querySelector('.sar-popup-overlay').classList.add('active');
        document.querySelector('html').classList.add('popup-active');
      } else {
        if (document.querySelector('#section-contact-form')) {
          e.preventDefault();
          e.stopPropagation();
          scrollToElem('#section-contact-form');
        }
      }
    }
  });

  /**
   * Open pop-up form. First, we close the mobile menu, then the popup appears
   */
  document.querySelector('.off-canvas-menu-container .custom-menu-item-email').addEventListener('click', function (e) {
    e.preventDefault();

    closeMobileMenu();
    setTimeout(() => {
      document.querySelector('header .custom-menu-item-email').click();
    }, 700);
  });

  /**
   * Close popup form when clicking on the background or the close button
   */
  document.addEventListener('click', function (e) {
    if (e.target.matches('.sar-popup-overlay.active') || e.target.matches('.btn-close')) {
      e.preventDefault();
      document.querySelector('.sar-popup-overlay').classList.remove('active');
      document.querySelector('html').classList.remove('popup-active');
    }
  });

  /**
   * Close popup form and .popup_youtube on pressing the escape button
   */
  document.addEventListener('keyup', function (e) {
    if (e.key === 'Escape') {
      document.querySelector('.sar-popup-overlay').classList.remove('active');
      document.querySelector('html').classList.remove('popup-active');
      if (document.querySelector('.popup_youtube')) {
        document.querySelector('.popup_youtube').remove();
      }
    }
  });

  /**
   * Popup YouTube
   */
  document.addEventListener('click', function (e) {
    if (e.target.matches('.video-box .play-button > a')) {
      e.preventDefault();

      const videoId = new URL(e.target.href).searchParams.get('v');
      const div = document.createElement('div');
      const div_html = `
        <div class="content">
          <div class="iframe-wrapper">
            <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1&mute=0"></iframe>
          </div>
        </div>`;

      div.classList.add('popup_youtube');
      div.innerHTML = div_html;

      document.body.appendChild(div);
    } else {
      if (!e.target.matches('iframe') && document.querySelector('.popup_youtube'))
        document.querySelector('.popup_youtube').remove();
    }
  });

})(jQuery);
