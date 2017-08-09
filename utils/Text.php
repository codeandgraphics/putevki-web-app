<?php
	
namespace Utils;

class Text
{

	public static function countDiff($first, $second)
	{
		$result = new \stdClass();

		if($first === $second)
		{
			$result->count = 0;
			$result->text = 'столько же, как и вчера';
			$result->class = 'primary';
		}
		elseif($first > $second)
		{
			$result->count = round( $first - $second );
			$result->text = 'на ' . $result->count . ' больше';
			$result->class = 'success';
		}
		else
		{
			$result->count = round( $second - $first );
			$result->text = 'на ' . $result->count . ' меньше';
			$result->class = 'danger';
		}

		return $result;
	}

	public static function lreplace($search, $replace, $subject)
	{
	    $pos = strrpos($subject, $search);
	
	    if($pos !== false)
	    {
	        $subject = substr_replace($subject, $replace, $pos, strlen($search));
	    }
	
	    return $subject;
	}
	
	public static function translite($string, $gost=false)
	{
		if($gost)
		{
			$replace = array(
				"А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
				"Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
				"Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n","О"=>"O","о"=>"o",
				"П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t","У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f",
				"Х"=>"Kh","х"=>"kh","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch","Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch",
				"Ы"=>"Y","ы"=>"y","Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>""
			);
		}
		else
		{
			$arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
			$arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");        
			$arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");
			    
			$replace = array(
				"А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
				"Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
				"Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
				"О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
				"У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"Kh","х"=>"kh","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
				"Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
				"Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye"
			);
			
			$string = str_replace($arStrES, $arStrRS, $string);
			$string = str_replace($arStrOS, $arStrRS, $string);
		}
		
		return iconv('UTF-8', 'UTF-8//IGNORE', strtr($string,$replace));
	}

	public static function strftime($format, $dateString)
	{
		$date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
		return $date ? strftime($format, $date->getTimestamp()) : false;
	}

	public static function formatToDayMonth($dateString, $format)
	{
		$date = \DateTime::createFromFormat($format, $dateString);

		return $date ? $date->format('d ') . self::humanize('months', $date->format('n')) : false;
	}

	public static function cleanPhone($phone) {
		return preg_replace('/\D+/', '', $phone);
	}

	public static function humanize($type, $value)
	{
		if($type === 'price')
		{
			return number_format($value, 2, '.', ' ');
		}
		if($type === 'people')
		{
			if($value >= 2 && $value <= 4) {
			    return $value . ' человека';
            }
			return $value . ' человек';
		}
		if($type === 'adults')
		{
			if($value === 1) {
			    return 'взрослый';
            }
			return 'взрослых';
		}
		if($type === 'kids')
		{
			if($value === 1) {
			    return 'ребенок';
            }
			if($value >= 5) {
			    return 'детей';
            }
			return 'ребенка';
		}
		if($type === 'rating')
		{
			if($value < 4) {
			    return 'cкромный отель';
            }
			if($value < 4.5) {
			    return 'хороший отель';
            }
			if($value <= 5) {
			    return 'отличный отель';
            }
		}
		
		if($type === 'nights')
		{
			$value = (int) $value;
			
			if($value >= 5 && $value <= 20) {
			    return $value . ' ночей';
            }
			if($value%10 === 1) {
			    return $value . ' ночь';
            }
			if($value%10 >= 2 && $value%10 < 5) {
			    return $value . ' ночи';
            }
			if($value%10 >= 5) {
			    return $value . ' ночей';
            }
		}
		
		if($type === 'months')
		{
			$months = [
			    '',
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря'
            ];
			
			return $months[$value];
		}
		
		if($type === 'types')
		{
			$types = array(
				'active'	=> 'Активный',
				'relax'		=> 'Спокойный',
				'family'	=> 'Семейный',
				'health'	=> 'Здоровый',
				'city'		=> 'Городской',
				'beach'		=> 'Пляжный',
				'deluxe'	=> 'Экслюзив'
			);
			
			return $types[$value];
		}
		
		if($type === 'meal')
		{
			$meals = [
				'RO'	=> 'Без питания',
				'BB'	=> 'Только завтраки',
				'HB'	=> 'Завтрак и ужин',
				'FB'	=> 'Завтрак, обед и ужин',
				'AI'	=> 'Все включено',
				'UAI'	=> 'Ультра все включено'
			];
			
			return $meals[$value];
		}
	}
}