(function ($) {

  /**
   * Counter animation
   */
  const countersContainer = $('#section-counter, .counter-column');
  let executed = false;
  $(window).scroll(function () {
    let scrollVal = $(this).scrollTop();
    countersContainer.each(function () {
      if (scrollVal >= $(this).offset().top - 500 && !executed) {
        counterAnimation();
        executed = true;
      }
    });
  });

  /**
   * Counter animation
   */
  function counterAnimation() {
    const countersText = $('.iwithtext .iwt-text h3', countersContainer);

    const counters = [];
    let text;

    countersText.each(function (i) {
      text = $(this).html();
      $(this).html('<span class="count"></span>' + (text.match(/\D+/) ? text.match(/\D+/)[0] : ''));

      counters[i] = {
        obj: $(this),
        num: text.match(/\d+/) ? parseInt(text.match(/\d+/)) : null,
      };
    });

    function count(start, value, id) {
      let localStart = start;
      let timeOut = 0;

      if (value <= 10) {
        timeOut = 250;
      } else if (value < 500 && value > 10) {
        timeOut = 20;
      } else if (value >= 500) {
        timeOut = 0.2;
      }

      setInterval(function () {
        if (localStart < value) {
          localStart++;
          counters[id].obj.find('.count').html(localStart);
        }
      }, timeOut);
    }

    for (let j = 0; j < counters.length; j++) {
      count(0, counters[j].num, j);
    }
  }
})(jQuery);