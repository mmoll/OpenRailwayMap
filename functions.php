<?php
/*
OpenRailwayMap Copyright (C) 2012 Alexander Matheisen
This program comes with ABSOLUTELY NO WARRANTY.
This is free software, and you are welcome to redistribute it under certain conditions.
See https://wiki.openstreetmap.org/wiki/OpenRailwayMap for details.
*/

namespace OpenRailwayMap;

require_once('config.php');

use OpenRailwayMap\Config;

class Functions
{

	/**
	 * base part of the server url, must end with '/'
	 */
	public function getUrlBase(): string
	{
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
			$urlbase = 'https://';
			$defaultport = 443;
		} else {
			$urlbase = 'http://';
			$defaultport = 80;
		}

		$urlbase .= $_SERVER['SERVER_NAME'];

		if ($_SERVER['SERVER_PORT'] != $defaultport) {
			$urlbase .= ':' . $_SERVER['SERVER_PORT'];
		}

		$urlbase .= $_SERVER['CONTEXT_PREFIX'];

		$subdir = dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['CONTEXT_DOCUMENT_ROOT'])));

		if ($subdir === '.') {
			$subdir = '';
		}
		$urlbase .= $subdir . '/';
		return $urlbase;
	}

	// sets a language file for gettext, either in the given or the most matching language
	public function includeLocale(string $lang): void
	{
		if ((!$lang) || (!array_key_exists($lang, Config::LANGS))) {
			$lang = $this->getUserLang();
		}

		putenv('LANGUAGE=');
		setlocale(LC_ALL, Config::LANGS[$lang][0]);
		bind_textdomain_codeset("messages", "UTF-8");
		bindtextdomain("messages", "locales");
		textdomain("messages");
	}


	// return an array of the user's languages, sorted by importance
	public function getLangs(): array
	{
		$header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$lang = explode(",", $header);

		$i = 0;
		$langs = [];
		foreach ($lang as $value) {
			$entry = explode(";", $value);
			$langpair = explode("-", $entry[0]);
			$langs[$i] = $langpair[0];
			$i++;
		}

		return $langs;
	}


	// returns the most matching language of the user
	public function getUserLang(): string
	{
		// read out language from header as array
		$langlist = $this->getLangs();

		// choose most matching language from available langs
		foreach ($langlist as $value) {
			if (array_key_exists($value, Config::LANGS)) {
				return $value;
			}
		}

		// if no matching language could be found, choose english
		return 'en';
	}


	// checks if given type-parameter is valid
	public function isValidType(?string $type): bool
	{
		if (!isset($type, $_GET[$type]) || !$type) {
			return false;
		}

		$type = $_GET[$type];
		// check if given object type is invalid
		return !in_array($type, ['node', 'way', 'relation']);
	}


	// checks if given osm id is valid
	public function isValidInteger(?string $input): bool
	{
		if (!isset($input, $_GET[$input]) || !$input) {
			return false;
		}

		$input = $_GET[$input];
		if (!ctype_digit($input)) {
			return false;
		}

		return true;
	}


	// checks if given coordinate is valid
	public function isValidCoordinate(?string $coord): bool
	{
		if (!isset($coord, $_GET[$coord]) || !$coord) {
			return false;
		}

		$coord = $_GET[$coord];
		return is_numeric($coord);
	}


	// checks if given timezone offset is valid
	public function isValidOffset(?string $offset): bool
	{
		if (!isset($offset, $_GET[$offset]) || !$offset) {
			return false;
		}

		$offset = $_GET[$offset];
		return is_numeric($offset);
	}


	public function urlArgsToParam(bool $checkMobile, string $urlbase)
	{
		echo "<script type=\"text/javascript\">\n";
		echo "var params={\n";
		echo "urlbase : '" . $urlbase . "',\n";
		echo "id : " . ($this->isValidInteger('id') ? ($_GET['id']) : ("null")) . ",\n";
		echo "type : " . ($this->isValidType('type') ? ("'" . $_GET['type'] . "'") : ("null")) . ",\n";
		echo "lat : ";
		if ($this->isValidCoordinate('lat')) {
			echo $_GET['lat'] . ",\n";
		} else {
			echo "null,\n";
		}
		echo "lon : ";
		if ($this->isValidCoordinate('lon')) {
			echo $_GET['lon'] . ",\n";
		} else {
			echo "null,\n";
		}
		echo "zoom : " . ($this->isValidInteger('zoom') ? ($_GET['zoom']) : ("null")) . ",\n";
		echo "lang : " . (isset($_GET['lang']) ? ("'" . $_GET['lang'] . "'") : ("null")) . ",\n";
		echo "offset : " . ($this->isValidOffset('offset') ? ($_GET['offset']) : ("null")) . ",\n";
		echo "searchquery : " . (isset($_GET['q']) ? (json_encode($_GET['q'])) : ("''")) . ",\n";
		echo "ref : " . (isset($_GET['ref']) ? (json_encode($_GET['ref'])) : ("null")) . ",\n";
		echo "name : " . (isset($_GET['name']) ? (json_encode($_GET['name'])) : ("null")) . ",\n";
		echo "line : " . (isset($_GET['line']) ? (json_encode($_GET['line'])) : ("null")) . ",\n";
		echo "operator : " . (isset($_GET['operator']) ? (json_encode($_GET['operator'])) : ("null")) . ",\n";
		if ($checkMobile) {
			echo "mobile : " . (isset($_GET['mobile']) ? (($_GET['mobile'] != '0' && $_GET['mobile'] != 'false') ? "true" : "false") : ("null")) . ",\n";
		}
		echo "style : " . (isset($_GET['style']) ? (json_encode($_GET['style'])) : ("null")) . "\n";
		echo "};\n";
		echo "</script>\n";
	}

	public function writeLine(int $index, string $height, string $payload, string $caption): string
	{
		$line = "\t\t\t<tr><td";

		if ($height) {
			$line .= ' style="height: ' . $height . 'px;"';
		} else {
			$height = '16';
		}

		return $line . '><canvas width="80" height="' . $height . '" id="legend-' . (string) $index
			. '" data-geojson=' . "'" . $payload
			. "'></canvas></td>\n\t\t\t\t<td>"
			. htmlspecialchars(_($caption), ENT_COMPAT) . "</td></tr>\n";
	}
}
