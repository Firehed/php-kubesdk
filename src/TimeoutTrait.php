<?php
declare(strict_types=1);

namespace Kubernetes;

trait TimeoutTrait
{
    /** @var int */
    protected $timeoutMs = Api::DEFAULT_TIMEOUT_MS;

    /** @var int */
    protected $connectTimeoutMs = Api::DEFAULT_CONNECT_TIMEOUT_MS;

    protected function getCurlTimeoutOptions(): array
    {
        return [
            CURLOPT_CONNECTTIMEOUT_MS => $this->connectTimeoutMs,
            CURLOPT_TIMEOUT_MS => $this->timeoutMs,
        ];
    }

    public function setTimeoutMs(int $timeout): void
    {
        $this->timeoutMs = $timeout;
    }

    public function setConnectTimeoutMs(int $timeout): void
    {
        $this->connectTimeoutMs = $timeout;
    }
}
