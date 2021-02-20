<?php
	/*
	OpenRailwayMap Copyright (C) 2012 Alexander Matheisen
	This program comes with ABSOLUTELY NO WARRANTY.
	This is free software, and you are welcome to redistribute it under certain conditions.
	See https://wiki.openstreetmap.org/wiki/OpenRailwayMap for details.
	*/


	require_once("config.php");


	// sets a language file for gettext, either in the given or the most matching language
	function includeLocale($lang)
	{
		global $langs;

		if ((!$lang) || (!array_key_exists($lang, $langs))) {
			$lang = getUserLang();
		}

		putenv('LANGUAGE=');
		setlocale(LC_ALL, $langs[$lang][0]);
		bind_textdomain_codeset("messages", "UTF-8");
		bindtextdomain("messages", "locales");
		textdomain("messages");
	}


	// return an array of the user's languages, sorted by importance
	function getLangs(): array
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
	function getUserLang(): string
	{
		global $langs;

		// read out language from header as array
		$langlist = getLangs();

		// choose most matching language from available langs
		foreach ($langlist as $value) {
			if (array_key_exists($value, $langs)) {
				return $value;
			}
		}

		// if no matching language could be found, choose english
		return "en";
	}


	// checks if given type-parameter is valid
	function isValidType($type): bool
	{
		if (!isset($type, $_GET[$type]) || !$type) {
			return false;
		}

		$type = $_GET[$type];
		// check if given object type is invalid
		return !in_array($type,  ["node", "way", "relation"]);
	}


	// checks if given osm id is valid
	function isValidInteger($input): bool
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
	function isValidCoordinate($coord): bool
	{
		if (!isset($coord, $_GET[$coord]) || !$coord) {
			return false;
		}

		$coord = $_GET[$coord];
		return is_numeric($coord);
	}


	// checks if given timezone offset is valid
	function isValidOffset($offset): bool
	{
		if (!isset($offset, $_GET[$offset]) || !$offset) {
			return false;
		}

		$offset = $_GET[$offset];
		return is_numeric($offset);
	}


	function urlArgsToParam($checkMobile, $urlbase)
	{
		echo "<script type=\"text/javascript\">\n";
			echo "var params={\n";
			echo "urlbase : '" . $urlbase . "',\n";
			echo "id : ".(isValidInteger('id') ? ($_GET['id']) : ("null")).",\n";
			echo "type : ".(isValidType('type') ? ("'".$_GET['type']."'") : ("null")).",\n";
			echo "lat : ";
				if (isValidCoordinate('lat')) {
					echo $_GET['lat'] . ",\n";
				} else {
					echo "null,\n";
				}
			echo "lon : ";
				if (isValidCoordinate('lon')) {
					echo $_GET['lon'] . ",\n";
				} else {
					echo "null,\n";
				}
			echo "zoom : " . (isValidInteger('zoom') ? ($_GET['zoom']) : ("null")) . ",\n";
			echo "lang : " . (isset($_GET['lang']) ? ("'".$_GET['lang']."'") : ("null")) . ",\n";
			echo "offset : " . (isValidOffset('offset') ? ($_GET['offset']) : ("null")) . ",\n";
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
