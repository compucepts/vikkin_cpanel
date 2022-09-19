$(window).on('load', function () {
  $('#preloader').fadeOut('slow', function () {
    $(this).remove();
  });
});
$(document).ready(function () {
  $('.inavigation a[href*=#].link').bind('click', function (e) {
    e.preventDefault();
    var target = $(this).attr("href");
    $('html, body').stop().animate({
      scrollTop: $(target).offset().top
    }, 600, function () {
      location.hash = target;
    });
    return false;
  });
  $(window).scroll(function () {
    const scrollDistance = $(window).scrollTop() + 160;
    $('section').each(function (i) {
      if ($(this).position().top <= scrollDistance) {
        $('.inavigation a.active').removeClass('active').removeAttr("aria-current");
        $('.inavigation a').eq(i).addClass('active').attr("aria-current", "page");
      }
    });
  }).scroll();
});


$('a[href=#top]').click(function () {
  $('body,html').animate({
    scrollTop: 0
  }, 600);
  return false;
});
$(window).scroll(function () {
  if ($(this).scrollTop() > 50) {
    $('.gotop a').fadeIn();
    $('.menu').addClass('menuBGOnScroll');
  } else {
    $('.gotop a').fadeOut();
    $('.menu').removeClass('menuBGOnScroll');
  }
});
(function ($) {
  $('.nav-toggle').click(function (e) {
    e.preventDefault();
    $('.nav-toggle').toggleClass('active');
    $('.nav-menu').toggleClass('active');
    $('.nav-overlay').toggleClass('active');
  })
  $('.nav-overlay').click(function (e) {
    e.preventDefault();
    $('.nav-toggle').toggleClass('active');
    $('.nav-menu').toggleClass('active');
    $('.nav-overlay').toggleClass('active');
  })
})(jQuery);

if ($(window).width() <= 992) {
  $(document).ready(function () {
    $('.inavigation a').click(function () {
      $('.nav-menu').removeClass('active');
      $('.nav-overlay').removeClass('active');
      $('.nav-toggle').removeClass('active');
    });

  });
}
$(document).ready(function () {
  $('form[id="contact-form"]').validate({
    rules: {
      name: 'required',
      subject: 'required',
      email: {
        required: true,
        email: true,
      },
      message: {
        required: true,
        minlength: 20,
      }
    },
    messages: {
      name: 'This field is required',
      subject: 'This field is required',
      email: {
        required: "This field is required",
        email: 'Enter a valid email',
      },
      message: {
        minlength: 'Message must be at least 20 characters long'
      }
    },
    submitHandler: function (form) {
      $('.loader').show();
      $('#contact-submit').prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "https://boundlessvc.io/email/send.php",
        data: $(form).serialize(),
        dataType: "json",
        success: function (data, textStatus) {
          $('.success').show();
          $('.loader').hide();
          setTimeout(() => {
            $('.success').fadeOut();
          }, 5000);
          $(form)[0].reset();
          $('#contact-submit').prop('disabled', false);
        },
        fail: function (xhr, textStatus, errorThrown) {
          $('.failed').show();
          $('.loader').hide();
          setTimeout(() => {
            $('.failed').fadeOut();
          }, 5000);
          $('#contact-submit').prop('disabled', false);
        }
      }).done(function (data) {
        $('.loader').hide();
        $('#contact-submit').prop('disabled', false);
      });
    }
  });
});