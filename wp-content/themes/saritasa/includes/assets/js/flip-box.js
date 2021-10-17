/***************** flip box min heights ******************/
//if content height exceeds min height change it

(function ($) {
  function flipBoxHeights() {
    $('.nectar-flip-box').each(function () {

      var $flipBoxMinHeight = parseInt($(this).attr('data-min-height'));
      var $flipBoxHeight = ($(this).find('.flip-box-back .inner').height() > $(this).find('.flip-box-front .inner').height()) ? $(this).find('.flip-box-back .inner').height() : $(this).find('.flip-box-front .inner').height();

      if ($flipBoxHeight >= $flipBoxMinHeight - 80) {
        $(this).find('> div').css('height', $flipBoxHeight + 80);
      } else
        $(this).find('> div').css('height', 'auto');
    });
  }

  flipBoxHeights();

  if (navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|BlackBerry|Opera Mini)/)) {
    $('body').on('click', '.nectar-flip-box', function () {
      $(this).toggleClass('flipped');
    });
  }

//touch
  if (navigator.userAgent.match(/(Android|iPod|iPhone|iPad|BlackBerry|IEMobile|Opera Mini)/) && $('.nectar-box-roll').length > 0) {
    $('body').swipe({
      tap: function (event, target) {
        if ($(target).parents('.nectar-flip-box').length > 0)
          $(target).parents('.nectar-flip-box').trigger('click');
        if ($(target).is('.nectar-flip-box'))
          $(target).trigger('click');
      },
      swipeStatus: function (event, phase, direction, distance, duration, fingers) {
        if ($('#slide-out-widget-area.open').length > 0) return false;
        if (direction == 'up') {
          boxRoll(null, -1);
          if ($('#ajax-content-wrap.no-scroll').length == 0) $('body').swipe('option', 'allowPageScroll', 'vertical');
        } else if (direction == 'down' && $(window).scrollTop() == 0) {
          boxRoll(null, 1);
          $('body').swipe('option', 'allowPageScroll', 'auto');
        }
      },
    });

  }
})(jQuery);
