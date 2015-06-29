<?php
namespace Observer;

class ObserverResponse {
	/** @var array */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}
}
