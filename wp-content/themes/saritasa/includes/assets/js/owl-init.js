(function ($) {

  let id = null;

  /**
   * Animation on the Join Our Team Page. Not used
   * @param elem
   */
  function changeBgSize(elem) {
    const mediaQuery = window.matchMedia('(min-width: 1000px)');
    if (mediaQuery.matches) {
      elem = $(elem);
      let height = 110;
      clearInterval(id);
      id = setInterval(changeWidth, 10);

      function changeWidth() {
        if (height < 51) {
          clearInterval(id);
        } else {
          height -= 0.025;
          elem.css({'backgroundSize': `auto ${height}vw`});
        }
      }
    }
  }

  function slideChangedEvent(elem) {
    elem.on('changed.owl.carousel', function (e) {
      const neededSlide = elem.find('.owl-item:not(.active) [class*=slide]');
      // changeBgSize(neededSlide);
    });
  }

  $(function(){
    // changeBgSize($('.owl-item.active [class*=slide]'));
  })

  const allCarouselsOnPage = $('.owl-carousel');
  const initCarouselWithProps = elem => {
    elem.owlCarousel({
      slideSpeed: elem.data('settings').speed,
      loop: true,
      rewind: true,
      autoplay: elem.data('settings').autorotate,
      autoplayHoverPause: true,
      center: true,
      responsive: {
        0: {
          items: elem.data('settings').mobile,
        },
        641: {
          items: elem.data('settings').tablet,
        },
        1000: {
          items: elem.data('settings').desktop,
        },
      },
    });
  };

  [...allCarouselsOnPage].forEach(item => {
    item = $(item);
    if (item.data('settings').off_at_screen_resolution !== '') {
      if (window.innerWidth < item.data('settings').off_at_screen_resolution) {
        initCarouselWithProps(item);
      } else {
        item.owlCarousel('destroy');
        item.addClass('off');
        item.find('.carousel-item').css({width: 100 / item.data('settings').desktop + '%'});
      }

      $(window).resize(() => {
        if (window.innerWidth < item.data('settings').off_at_screen_resolution) {
          initCarouselWithProps(item);
          item.removeClass('off');
          $('.carousel-item', item).css({width: ''});
        } else {
          item.owlCarousel('destroy');
          item.addClass('off');
          item.find('.carousel-item').css({width: 100 / item.data('settings').desktop + '%'});
        }
      });
    } else {
      initCarouselWithProps(item);
    }
    slideChangedEvent(item);
  });
})(jQuery);