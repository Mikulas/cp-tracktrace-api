<?php

namespace Mikulas;

require __DIR__ . '/vendor/autoload.php';


class Tracktrace
{

	const LOCALE_CS = 'CZ';
	const LOCALE_EN = 'EN';

	const URL = 'http://www.ceskaposta.cz/cz/nastroje/sledovani-zasilky.php?locale=%s&send.x=%d&send.y=%d&send=submit&go=ok&barcode=%s';
	const PATTERN = '~^[A-Z]{2}[0-9]{9,10}[A-Z]{1,2}$~i';


	public static function status($code, $locale = self::LOCALE_CS)
	{
		if (!self::validateCode($code)) {
			throw new \InvalidArgumentException("Invalid code `$code` given, expected `" . self::PATTERN . "`.");
		}
		$url = sprintf(self::URL, $locale, rand(1, 30), rand(1, 30), $code);

		$html = \Sunra\PhpSimple\HtmlDomParser::file_get_html($url);

		$statuses = [];
		foreach ($html->find('#content table td') as $status) {
			$statuses[] = $string = html_entity_decode(trim($status->plaintext), ENT_QUOTES, "utf-8");;
		}
		return $statuses;
	}



	protected static function validateCode($code)
	{
		return preg_match(self::PATTERN, $code);
	}

}
