<?php
declare(strict_types=1);

namespace Kubernetes;

interface Api
{
    const DEFAULT_CONNECT_TIMEOUT_MS = 500;
    const DEFAULT_TIMEOUT_MS = 3000;
    const VERSION = '1.1.0';

    /**
     * Perform a GET request to the specified endpoint.
     *
     * @param string $endpoint the K8S API endpoint
     * @return array the decoded JSON from the response
     */
    public function get(string $endpoint): array;

    /**
     * Set the network timeout. This corresponds to CURLOPT_TIMEOUT_MS
     *
     * @param int $timeout The network timeout, in milliseconds
     */
    public function setTimeoutMs(int $timeout): void;

    /**
     * Set the network connect timeout. This corresponds to
     * CURLOPT_CONNECTTIMEOUT_MS
     *
     * @param int $timeout The network connect timeout, in milliseconds
     */
    public function setConnectTimeoutMs(int $timeout): void;
}
