import $ from 'jquery';
import Inputmask from 'inputmask';
import Cookies from 'cookies-js';

const isMobile = /iPhone|iPod|Android/.test(navigator.userAgent) && !window.MSStream;

export function initActions() {
  Inputmask().mask(document.querySelectorAll('input'));

  const offset = 300;
  const offsetOpacity = 1200;
  const scrollTopDuration = 700;
  const $backToTop = $('#upButton');

  $(window).scroll(function windowScroll() {
    ($(this).scrollTop() > offset) ? $backToTop.addClass('is-visible') : $backToTop.removeClass('is-visible fade-out');
    if ($(this).scrollTop() > offsetOpacity) {
      $backToTop.addClass('fade-out');
    }
  });

  $backToTop.on('click', (event) => {
    event.preventDefault();
    $('body,html').animate({ scrollTop: 0 }, scrollTopDuration);
  });
}

export function mobileOverlay() {
  const isClosed = $.jStorage.get('mobile-overlay') === 'closed' || Cookies.get('mobile-overlay') === 'closed';

  if (isMobile && !isClosed) {
    $('body').addClass('disable-scroll');
    $('#mobile-overlay').removeClass('hidden').find('.close-overlay'); /*.on('click', () => {
      $('meta[name=viewport]').prop('content', 'width=1230');
      $('#mobile-overlay').addClass('hidden');
      $('body').removeClass('disable-scroll');
      Cookies.set('mobile-overlay', 'closed', { expires: 30 });
      $.jStorage.set('mobile-overlay', 'closed');

      return false;
    }); */
    $('meta[name=viewport]').prop('content', 'initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no');
  }
}

export default {};
