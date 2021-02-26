<?php

namespace OpenRailwayMap;

use JsonSerializable;

class JSONParams implements JsonSerializable
{
// checks if given type-parameter is valid
	private function isValidType(?string $type): bool
	{
		if (!$type) {
			return false;
		}

		return !in_array($type, ['node', 'way', 'relation']);
	}


// checks if given osm id is valid
	private function isValidInteger(?string $input): bool
	{
		if (!$input) {
			return false;
		}

		if (!ctype_digit($input)) {
			return false;
		}

		return true;
	}


// checks if given coordinate is valid
	private function isValidCoordinate(?string $coord): bool
	{
		if (!$coord) {
			return false;
		}

		return is_numeric($coord);
	}


// checks if given timezone offset is valid
	private function isValidOffset(?string $offset): bool
	{
		if (!$offset) {
			return false;
		}

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

	public function setUrlbase(string $urlbase): void
	{
		// probably set this via __construct() from Functions-function or completely move into this class.
		$this->urlbase = $urlbase;
	}

	public function setId(?string $id): void
	{
		if ($this->isValidInteger($id)) {
			$this->id = $id;
		}
	}

	public function setType(?string $type): void
	{
		if ($this->isValidType($type)) {
			$this->type = $type;
		}
	}

	public function setLat(?string $lat): void
	{
		if ($this->isValidCoordinate($lat)) {
			$this->lat = $lat;
		}
	}

	public function setLon(?string $lon): void
	{
		if ($this->isValidCoordinate($lon)) {
			$this->lon = $lon;
		}
	}

	public function setZoom(?string $zoom): void
	{
		if ($this->isValidInteger($zoom)) {
			$this->zoom = $zoom;
		}
	}

	public function setLang(?string $lang): void
	{
		$this->lang = $lang;
	}

	public function setOffset(?string $offset): void
	{
		if ($this->isValidOffset($offset)) {
			$this->offset = $offset;
		}
	}

	public function setSearchquery(?string $searchquery): void
	{
		$this->searchquery = $searchquery;
	}

	public function setRef(?string $ref): void
	{
		$this->ref = $ref;
	}

	public function setName(?string $name): void
	{
		$this->name = $name;
	}

	public function setLine(?string $line): void
	{
		$this->line = $line;
	}


	public function setOperator(?string $operator): void
	{
		$this->operator = $operator;
	}

	public function setMobile(bool $mobile): void
	{
		$this->mobile = $mobile;
	}

	public function setStyle(?string $style): void
	{
		$this->style = $style;
	}

	public function jsonSerialize(): array
	{
		$array = (array) $this;
		$result = array_filter($array, function($value) {
			return !is_null($value);
		});

		return $result;
	}
}
