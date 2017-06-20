function Humanize(type, value){
	
	var meals = {
		'RO'	: 'Без питания',
		'BB'	: 'Только завтраки',
		'HB'	: 'Завтрак и ужин',
		'FB'	: 'Завтрак, обед и ужин',
		'AI'	: 'Все включено',
		'UAI'	: 'Ультра все включено'
	}
	
	switch (type){
		
		case 'nights':
			if(value >= 5 && value <= 20)  return value + ' ночей';
			if(value%10 == 1) return value + ' ночь';
			if(value%10 >= 2 && value%10 < 5) return value + ' ночи';
			if(value%10 >= 5)  return value + ' ночей';
			break;
		
		case 'people':
			if(value >= 5 && value <= 20)  return value + ' человек';
			if(value%10 == 1) return value + ' человек';
			if(value%10 >= 2 && value%10 < 5) return value + ' человека';
			if(value%10 >= 5)  return value + ' человек';
			break;
		
		case 'adults':
			if(value == 1) return value + ' взрослый';
			return value + ' взрослых';
			break;
			
		case 'age':
			if(value == 1) return 'До 2х лет';
			if(value >= 2 && value <= 5) return value + ' года';
			return value + ' лет';
			break;
			
		case 'hotelsText':
			if(value%10 == 0) return ' отелей найдено';
			if(value >= 5 && value <= 20)  return ' отелей найдено';
			if(value%10 == 1) return ' отель найден';
			if(value%10 >= 2 && value%10 < 5) return ' отеля найдено';
			if(value%10 >= 5)  return ' отелей найдено';
			break;
			
		case 'tours':
			if(value >= 5 && value <= 20)  return value + ' туров';
			if(value%10 == 1) return value + ' тур';
			if(value%10 >= 2 && value%10 < 5) return value + ' тура';
			if(value%10 >= 5)  return value + ' туров';
			break;
			
		case 'rating':
			if(value < 4) return 'cкромный отель';
			if(value < 4.5) return 'хороший отель';
			if(value <= 5) return 'отличный отель';
			break;
			
		case 'price':			
			value = parseInt(value);
			value = value.format(0, 3, ' ');
			return value;
			break;
			
		case 'meals':
			return meals[value];
			break;
			
	}
};

var Locale = {
	days:["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Воскресенье"],
	daysShort:["Вос","Пон","Вто","Сре","Чет","Пят","Суб","Вос"],
	daysMin:["Вс","Пн","Вт","Ср","Чт","Пт","Сб","Вс"],
	months:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
	monthsCase:["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"],
	monthsShort:["янв","фев","мар","апр","мая","июн","июл","авг","сен","окт","ноя","дек"]
};