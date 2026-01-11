<?php

namespace Observer\EndpointClients;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ObserverPsr18Client implements ObserverEndpointClient {
	/**
	 * @param null|string $authToken
	 * @param ClientInterface $httpClient
	 * @param RequestFactoryInterface $requestFactory
	 * @param StreamFactoryInterface $streamFactory
	 * @param string $userAgent
	 */
	public function __construct(
		private null|string $authToken,
		private ClientInterface $httpClient,
		private RequestFactoryInterface $requestFactory,
		private StreamFactoryInterface $streamFactory,
		private string $userAgent = 'ObserverClient v2'
	) {}

	/**
	 * @param array $data
	 * @return string
	 */
	private function encodeForm(array $data) {
		if (defined('PHP_QUERY_RFC3986')) {
			return http_build_query($data, '', '&', PHP_QUERY_RFC3986);
		}
		return http_build_query($data, '', '&');
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 */
	public function call($url, array $data) {
		$request = $this->requestFactory->createRequest('POST', $url)
			->withHeader('User-Agent', $this->userAgent)
			->withHeader('Content-Type', 'application/x-www-form-urlencoded')
			->withHeader('Accept', 'application/json');

		if($this->authToken !== null) {
			$request = $request->withHeader('X-Token', $this->authToken);
		}

		$request = $request->withBody($this->streamFactory->createStream($this->encodeForm($data)));

		try {
			$response = $this->httpClient->sendRequest($request);
		} catch (\Exception $e) {
			return [];
		}

		$decoded = @json_decode((string) $response->getBody(), true);
		if (!is_array($decoded)) {
			return [];
		}

		return $decoded;
	}
}
