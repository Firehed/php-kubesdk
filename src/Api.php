<?php
declare(strict_types=1);

namespace Kubernetes;

interface Api
{
    const DEFAULT_CONNECT_TIMEOUT_MS = 500;
    const DEFAULT_TIMEOUT_MS = 3000;
    const VERSION = '0.0.3';

    public function get(string $endpoint): array;

    public function setTimeoutMs(int $timeout): void;

    public function setConnectTimeoutMs(int $timeout): void;
}
