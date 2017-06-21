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

Number.prototype.format = function(n, x, s, c) {
  const re = `\\d(?=(\\d{${x || 3}})+${n > 0 ? '\\D' : '$'})`;
  const num = this.toFixed(Math.max(0, ~~n));

  return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), `$&${s || ','}`);
};
