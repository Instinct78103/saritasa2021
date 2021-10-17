(function ($) {
  document.addEventListener('wpcf7mailsent', function (event) {
    if ('mail_sent' === event.detail.status) {
      location = '/thank-you';
    }
  }, false);

  const formContainer = $('form.wpcf7-form');
  const submitBtn = $('[type=button], [type=submit], button#btn-contact', formContainer);

  $('[type="tel"]', formContainer).mask('(000) 000-0000', {
    clearIncomplete: true,
  });

  $('[type=checkbox]', formContainer).attr('data-wc-ignore', 'true');

  $('.best_time_checkbox', formContainer).on('click', function () {
    let timeValues = [];
    $('[name="best_time_checkbox[]"]:checked').each(function (i) {
      timeValues[i] = $(this).val();
    });
    $('.best-time-to-call', formContainer).val(timeValues.join(','));
  });

  $('input, textarea, .call-radio', formContainer).on('classChanged', function () {
    if ($(this).is('.wpcf7-not-valid')) {
      let errorAlert = $('+ [role=alert]', this);
      errorAlert.remove();
    }
  });

  $('.ajax-loader', formContainer).on('classChanged', function () {
    if ($(this).is('.is-active')) {
      $(this).parent().addClass('load-is-active');
    } else {
      $(this).parent().removeClass('load-is-active');
    }
  });

  $('input, textarea', formContainer).on('focus click', function () {
    if ($('[type=tel], [type=email]').attr('placeholder') === 'Email or phone number required') {
      $('[type=email]').attr('placeholder', 'Email');
      $('[type=tel]').attr('placeholder', 'Phone #');

      $('.wpcf7-form input').removeClass('wpcf7-not-valid');
    }

    if ($(this).is('[type=checkbox]')) {
      $(this).closest('.call-radio').removeClass('wpcf7-not-valid');
    }

    if ($(this).is('[type=tel]') || $(this).is('[type=email]')) {
      $('[type=tel], [type=email]', formContainer).removeClass('wpcf7-not-valid');
    }
  });

  submitBtn.prop('type', 'button');
  submitBtn.click(function () {
    var tel = $('[type=tel]', formContainer).val().length;
    var email = $('[type=email]', formContainer).val().length;

    if (tel && email) {
      $('[type=email]', formContainer).attr('aria-required', true);
      $('[type=tel]', formContainer).attr('aria-required', true);
    } else if (tel) {
      $('[type=email]', formContainer).attr('aria-required', false);
    } else if (email) {
      $('[type=tel]', formContainer).attr('aria-required', false);
    } else {
      $('[type=tel], [type=email]', formContainer)
        .attr('placeholder', 'Email or phone number required')
        .attr('aria-required', true);
    }

    if (wpcf7) {
      wpcf7.submit(formContainer);
    }
  });
})(jQuery);