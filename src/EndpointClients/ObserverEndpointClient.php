<?php
namespace Observer\EndpointClients;

interface ObserverEndpointClient {
	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 */
	public function call($url, array $data);
}
