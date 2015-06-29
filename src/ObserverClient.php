<?php
namespace Observer;

use Observer\EndpointClients\ObserverEndpointClient;

class ObserverClient {
	/** @var string */
	private $endpointUrl;
	/** @var ObserverEndpointClient */
	private $endpointClient;

	/**
	 * @param string $endpointUrl
	 * @param ObserverEndpointClient $endpointClient
	 */
	public function __construct($endpointUrl, ObserverEndpointClient $endpointClient) {
		$this->endpointUrl = $endpointUrl;
		$this->endpointClient = $endpointClient;
	}

	/**
	 * @param string $projectKey
	 * @return ObserverProject
	 */
	public function getProject($projectKey) {
		return new ObserverProject($this, $projectKey);
	}

	/**
	 * @return string
	 */
	public function getEndpointUrl() {
		return $this->endpointUrl;
	}

	/**
	 * @return ObserverEndpointClient
	 */
	public function getEndpointClient() {
		return $this->endpointClient;
	}
}
