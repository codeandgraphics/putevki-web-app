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

export function getQuery() {
  const vars = [];
  let hash;

  const hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for (let i = 0; i < hashes.length; i++) {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}

export function serializeForm($form) {
  let json = {};
  const pushCounters = {};
  const patterns = {
    validate: /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
    key: /[a-zA-Z0-9_]+|(?=\[\])/g,
    push: /^$/,
    fixed: /^\d+$/,
    named: /^[a-zA-Z0-9_]+$/,
  };

  const build = (b, key, value) => {
    const base = b;
    base[key] = value;
    return base;
  };

  const pushCounter = (key) => {
    if (pushCounters[key] === undefined) {
      pushCounters[key] = 0;
    }
    pushCounters[key] += 1;
    return pushCounters[key];
  };

  $form.serializeArray().forEach((item) => {
    if (!patterns.validate.test(item.name)) {
      return;
    }

    const keys = item.name.match(patterns.key);
    let merge = item.value;
    let reverseKey = item.name;
    let k = keys.pop();

    while (k !== undefined) {
      reverseKey = reverseKey.replace(new RegExp(`\\[${k}\\]$`), '');

      if (k.match(patterns.push)) {
        merge = build([], pushCounter(reverseKey), merge);
      } else if (k.match(patterns.fixed)) {
        merge = build([], k, merge);
      } else if (k.match(patterns.named)) {
        merge = build({}, k, merge);
      }

      k = keys.pop();
    }

    json = $.extend(true, json, merge);
  });

  return json;
}


Number.prototype.format = function (n, x, s, c) {
  const re = `\\d(?=(\\d{${x || 3}})+${n > 0 ? '\\D' : '$'})`;
  const num = this.toFixed(Math.max(0, ~~n));

  return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), `$&${s || ','}`);
};
