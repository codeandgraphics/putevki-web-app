export function types(value) {
  switch (value) {
    case 'active':
      return 'Активный';
    case 'beach':
      return 'Пляжный';
    case 'city':
      return 'Городской';
    case 'deluxe':
      return 'Эксклюзивный';
    case 'family':
      return 'Семейный';
    case 'health':
      return 'Оздоровительный';
    case 'relax':
      return 'Спокойный';
    default:
      return '';
  }
}

export function nights(value) {
  if (value >= 5 && value <= 20) return `${value} ночей`;
  if (value % 10 === 1) return `${value} ночь`;
  if (value % 10 >= 2 && value % 10 < 5) return `${value} ночи`;
  return `${value} ночей`;
}

export function people(value) {
  if (value >= 5 && value <= 20) return `${value} человек`;
  if (value % 10 === 1) return `${value} человек`;
  if (value % 10 >= 2 && value % 10 < 5) return `${value} человека`;
  return `${value} человек`;
}

export function adults(value) {
  if (value === 1) return `${value} взрослый`;
  return `${value} взрослых`;
}

export function age(value) {
  if (value === 1) return 'До 2х лет';
  if (value >= 2 && value <= 5) return `${value} года`;
  return `${value} лет`;
}

export function hotelsFound(value) {
  if (value % 10 === 0) return ' отелей найдено';
  if (value >= 5 && value <= 20) return ' отелей найдено';
  if (value % 10 === 1) return ' отель найден';
  if (value % 10 >= 2 && value % 10 < 5) return ' отеля найдено';
  return ' отелей найдено';
}

export function tours(value) {
  if (value >= 5 && value <= 20) return `${value} путёвок`;
  if (value % 10 === 1) return `${value} путёвку`;
  if (value % 10 >= 2 && value % 10 < 5) return `${value} путёвки`;
  return `${value} путёвок`;
}

export function rating(value) {
  if (value > 4.5) return 'отличный отель';
  if (value > 4) return 'хороший отель';
  return 'cкромный отель';
}

export function price(value) {
  return parseInt(value, 10).format(0, 3, ' ');
}

export default {
  nights,
  people,
  adults,
  age,
  hotelsFound,
  tours,
  rating,
  price,
};
