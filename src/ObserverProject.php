<?php
namespace Observer;

class ObserverProject {
	/** @var ObserverClient */
	private $client;
	/** @var string */
	private $key;

	/**
	 * @param ObserverClient $client
	 * @param string $key
	 */
	public function __construct(ObserverClient $client, $key) {
		$this->client = $client;
		$this->key = $key;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @param string $entityKey
	 * @return ObserverPing
	 */
	public function createPing($entityKey) {
		return new ObserverPing($this->client, $this, $entityKey);
	}
}
