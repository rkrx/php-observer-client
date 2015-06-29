<?php
namespace Observer\EndpointClients;

class ObserverCurlClientObserver implements ObserverEndpointClient {
	/** @var string */
	private $authToken;

	/**
	 * @param string $authToken
	 */
	public function __construct($authToken) {
		$this->authToken = $authToken;
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 */
	public function call($url, array $data) {
		$ch = curl_init();

		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => ['X-Token' => $this->authToken],
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_USERAGENT => 'ObserverClient v2',
			CURLOPT_VERBOSE => false,
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 2,
			CURLOPT_TIMEOUT => 2,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data,
		);
		curl_setopt_array($ch, $options);
		$responseStr = curl_exec($ch);
		curl_close($ch);
		$response = @json_decode($responseStr, true);
		return $response;
	}
}
