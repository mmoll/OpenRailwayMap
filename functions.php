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

		if ((!$lang) || (!array_key_exists($lang, $langs)))
			$lang = getUserLang();

		putenv('LANGUAGE=');
		setlocale(LC_ALL, $langs[$lang][0]);
		bind_textdomain_codeset("messages", "UTF-8");
		bindtextdomain("messages", "locales");
		textdomain("messages");
	}


	// return an array of the user's languages, sorted by importance
	function getLangs()
	{
		$header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$lang = explode(",", $header);

		$i = 0;
		foreach ($lang as $value)
		{
			$entry = explode(";", $value);
			$langpair = explode("-", $entry[0]);
			$langs[$i] = $langpair[0];
			$i++;
		}

		return $langs;
	}


	// returns the most matching language of the user
	function getUserLang()
	{
		global $langs;

		// read out language from header as array
		$langlist = getLangs();

		// choose most matching language from available langs
		foreach ($langlist as $value)
			if (array_key_exists($value, $langs))
				return $value;

		// if no matching language could be found, choose english
		return "en";
	}


	// checks if given type-parameter is valid
	function isValidType($type)
	{
		if (!$type || !isset($type) || !isset($_GET[$type]))
			return false;

		$type = $_GET[$type];
		// check if given object type is invalid
		if (($type != "node") && ($type != "way") && ($type != "relation"))
			return false;

		return true;
	}


	// checks if given osm id is valid
	function isValidInteger($input)
	{
		if (!$input || !isset($input) || !isset($_GET[$input]))
			return false;

		$input = $_GET[$input];
		if (!ctype_digit($input))
			return false;

		return true;
	}


	// checks if given coordinate is valid
	function isValidCoordinate($coord)
	{
		if (!$coord || !isset($coord) || !isset($_GET[$coord]))
			return false;

		$coord = $_GET[$coord];
		if (!is_numeric($coord))
			return false;

		return true;
	}


	// checks if given timezone offset is valid
	function isValidOffset($offset)
	{
		if (!$offset || !isset($offset) || !isset($_GET[$offset]))
			return false;

		$offset = $_GET[$offset];
		if (!is_numeric($offset))
			return false;

		return true;
	}


	function urlArgsToParam($checkMobile, $urlbase)
	{
		echo "<script type=\"text/javascript\">\n";
			echo "var params={\n";
			echo "urlbase : '" . $urlbase . "',\n";
			echo "id : ".(isValidInteger('id') ? ($_GET['id']) : ("null")).",\n";
			echo "type : ".(isValidType('type') ? ("'".$_GET['type']."'") : ("null")).",\n";
			echo "lat : ";
				if (isValidCoordinate('lat'))
					echo $_GET['lat'].",\n";
				else
					echo "null,\n";
			echo "lon : ";
				if (isValidCoordinate('lon'))
					echo $_GET['lon'].",\n";
				else
					echo "null,\n";
			echo "zoom : " . (isValidInteger('zoom') ? ($_GET['zoom']) : ("null")) . ",\n";
			echo "lang : " . (isset($_GET['lang']) ? ("'".$_GET['lang']."'") : ("null")) . ",\n";
			echo "offset : " . (isValidOffset('offset') ? ($_GET['offset']) : ("null")) . ",\n";
			echo "searchquery : " . (isset($_GET['q']) ? (json_encode($_GET['q'])) : ("''")) . ",\n";
			echo "ref : " . (isset($_GET['ref']) ? (json_encode($_GET['ref'])) : ("null")) . ",\n";
			echo "name : " . (isset($_GET['name']) ? (json_encode($_GET['name'])) : ("null")) . ",\n";
			echo "line : " . (isset($_GET['line']) ? (json_encode($_GET['line'])) : ("null")) . ",\n";
			echo "operator : " . (isset($_GET['operator']) ? (json_encode($_GET['operator'])) : ("null")) . ",\n";
			if ($checkMobile)
				echo "mobile : " . (isset($_GET['mobile']) ? (($_GET['mobile'] != '0' && $_GET['mobile'] != 'false') ? "true" : "false") : ("null")) . ",\n";
			echo "style : " . (isset($_GET['style']) ? (json_encode($_GET['style'])) : ("null")) . "\n";
			echo "};\n";
		echo "</script>\n";
	}
?>
