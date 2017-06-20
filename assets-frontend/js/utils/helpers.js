import $ from 'jquery';

export function isScrolledIntoView(elem) {
  const $elem = $(elem);
  const $window = $(window);

  const docViewTop = $window.scrollTop();
  const docViewBottom = docViewTop + $window.height();

  const elemTop = $elem.offset().top;
  const elemBottom = elemTop + $elem.height();

  return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

