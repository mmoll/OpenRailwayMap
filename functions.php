<?php
/*
OpenRailwayMap Copyright (C) 2012 Alexander Matheisen
This program comes with ABSOLUTELY NO WARRANTY.
This is free software, and you are welcome to redistribute it under certain conditions.
See https://wiki.openstreetmap.org/wiki/OpenRailwayMap for details.
*/

namespace OpenRailwayMap;

require_once('config.php');
require_once('jsonparams.php');

use OpenRailwayMap\Config;

class Functions
{

	/**
	 * base part of the server url, must end with '/'
	 */
	private function getUrlBase(): string
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
	private function getLangs(): array
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

    private function getParameter(string $parameter): ?string
    {
        if (isset($_GET[$parameter])) {
            $value = $_GET[$parameter];
            if ($value !== '') {
                return $value;
            }
        }
        return null;
    }

	public function urlArgsToParam(bool $checkMobile): string
	{
		$jsonParams = new JSONParams();

		$jsonParams->setUrlbase($this->getUrlBase());
        $jsonParams->setId($this->getParameter('id'));
        $jsonParams->setType($this->getParameter('type'));
        $jsonParams->setLat($this->getParameter('lat'));
        $jsonParams->setLon($this->getParameter('lon'));
        $jsonParams->setZoom($this->getParameter('zoom'));
        $jsonParams->setLang($this->getParameter('lang'));
        $jsonParams->setOffset($this->getParameter('offset'));
        $jsonParams->setSearchquery($this->getParameter('q'));
        $jsonParams->setRef($this->getParameter('ref'));
        $jsonParams->setName($this->getParameter('name'));
        $jsonParams->setLine($this->getParameter('line'));
        $jsonParams->setOperator($this->getParameter('operator'));
        $jsonParams->setStyle($this->getParameter('style'));

		if ($checkMobile && isset($_GET['mobile'])) {
            if ($_GET['mobile'] !== '0' && $_GET['mobile'] !== 'false') {
                $jsonParams->setMobile(true);
            } else {
                $jsonParams->setMobile(false);
            }
        }

		$json = json_encode($jsonParams, JSON_THROW_ON_ERROR);

		return $json;
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
