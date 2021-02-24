<?php

namespace OpenRailwayMap;

class JSONParams
{
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

	public $urlbase;
	public $id;
	public $type;
	public $lat;
	public $lon;
	public $zoom;
	public $lang;
	public $offset;
	public $searchquery;
	public $ref;
	public $name;
	public $line;
	public $operator;
	public $mobile;
	public $style;

	/**
	 * @param mixed $urlbase
	 */
	public function setUrlbase($urlbase): void
	{
		// probably set this via __construct() from Functions-function or completely move into this class.
		$this->urlbase = $urlbase;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id): void
	{
		if ($this->isValidInteger($id)) {
			$this->id = $id;
		}
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type): void
	{
		if ($this->isValidType($type)) {
			$this->type = $type;
		}
	}

	/**
	 * @param mixed $lat
	 */
	public function setLat($lat): void
	{
		if ($this->isValidCoordinate($lat)) {
			$this->lat = $lat;
		}
	}

	/**
	 * @param mixed $lon
	 */
	public function setLon($lon): void
	{
		if ($this->isValidCoordinate($lon)) {
			$this->lon = $lon;
		}
	}

	/**
	 * @param mixed $zoom
	 */
	public function setZoom($zoom): void
	{
		if ($this->isValidInteger($zoom)) {
			$this->zoom = $zoom;
		}
	}

	/**
	 * @param mixed $lang
	 */
	public function setLang($lang): void
	{
		$this->lang = $lang;
	}

	/**
	 * @param mixed $offset
	 */
	public function setOffset($offset): void
	{
		if ($this->isValidOffset($offset)) {
			$this->offset = $offset;
		}
	}

	/**
	 * @param mixed $searchquery
	 */
	public function setSearchquery($searchquery): void
	{
		$this->searchquery = $searchquery;
	}

	/**
	 * @param mixed $ref
	 */
	public function setRef($ref): void
	{
		$this->ref = $ref;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name): void
	{
		$this->name = $name;
	}

	/**
	 * @param mixed $line
	 */
	public function setLine($line): void
	{
		$this->line = $line;
	}

	/**
	 * @param mixed $operator
	 */
	public function setOperator($operator): void
	{
		$this->operator = $operator;
	}

	/**
	 * @param mixed $mobile
	 */
	public function setMobile($mobile): void
	{
		$this->mobile = $mobile;
	}

	/**
	 * @param mixed $style
	 */
	public function setStyle($style): void
	{
		$this->style = $style;
	}
}
