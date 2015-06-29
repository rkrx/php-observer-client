<?php
namespace Observer;

class ObserverPing {
	/** @var ObserverClient */
	private $client;
	/** @var ObserverProject */
	private $project;
	/** @var string */
	private $entityKey;
	/** @var int */
	private $nextTimeout = null;
	/** @var string */
	private $message = null;
	/** @var string */
	private $messageType = null;
	/** @var float */
	private $runtime = null;

	/**
	 * @param ObserverClient $client
	 * @param ObserverProject $project
	 * @param string $entityKey
	 */
	public function __construct(ObserverClient $client, ObserverProject $project, $entityKey) {
		$this->client = $client;
		$this->project = $project;
		$this->entityKey = $entityKey;
		$this->setMessageIsInformation();
	}

	/**
	 * @param int $seconds
	 * @return $this
	 */
	public function setRuntime($seconds) {
		$this->runtime = (float) $seconds;
		return $this;
	}

	/**
	 * @return self
	 */
	public function setNoNextTimeout() {
		$this->setNextTimeoutTimestamp(0);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setStandardTimeout() {
		$this->nextTimeout = null;
		return $this;
	}

	/**
	 * @param int $count
	 * @return $this
	 */
	public function addTimeoutSeconds($count) {
		$this->nextTimeout = strtotime("+{$count} second", $this->nextTimeout);
		return $this;
	}

	/**
	 * @param int $count
	 * @return $this
	 */
	public function addTimeoutMinutes($count) {
		$this->nextTimeout = strtotime("+{$count} minute", $this->nextTimeout);
		return $this;
	}

	/**
	 * @param int $count
	 * @return $this
	 */
	public function addTimeoutHours($count) {
		$this->nextTimeout = strtotime("+{$count} hour", $this->nextTimeout);
		return $this;
	}

	/**
	 * @param int $count
	 * @return $this
	 */
	public function addTimeoutDays($count) {
		$this->nextTimeout = strtotime("+{$count} day", $this->nextTimeout);
		return $this;
	}

	/**
	 * @param int $timestamp
	 * @return $this
	 */
	public function setNextTimeoutTimestamp($timestamp) {
		$this->nextTimeout = (int) $timestamp;
		return $this;
	}

	/**
	 * @param string $message
	 * @return $this
	 */
	public function setMessage($message) {
		$this->message = (string) $message;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setMessageIsError() {
		$this->messageType = 'error';
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setMessageIsWarning() {
		$this->messageType = 'warning';
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setMessageIsInformation() {
		$this->messageType = 'information';
		return $this;
	}

	/**
	 * @return array
	 */
	public function ping() {
		$endpointUrl = $this->client->getEndpointUrl();

		if($endpointUrl === null) {
			return array();
		}

		$data = array();

		$data['ob'] = $this->project->getKey();
		$data['key'] = $this->entityKey;

		if($this->nextTimeout !== null) {
			$data['next'] = date('Y-m-d H:i:s', $this->nextTimeout);
		}

		if ($this->message !== null) {
			$data['message'] = $this->message;
			$data['message-type'] = $this->messageType;
		}

		if ($this->runtime !== null) {
			$data['runtime'] = $this->runtime;
		}

		$response = $this->client->getEndpointClient()->call($endpointUrl, $data);

		if (!is_array($response)) {
			$response = array();
		}

		return new ObserverResponse($response);
	}
}
