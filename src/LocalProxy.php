<?php
declare(strict_types=1);

namespace Kubernetes;

use RuntimeException;

class LocalProxy implements Api
{
    use CurlTrait;
    use TimeoutTrait;

    /** @var string */
    private $baseUrl;

    public function __construct(string $url)
    {
        $this->baseUrl = $url;
    }

    public function get(string $url): array
    {
        $params = $this->getCurlTimeoutOptions();

        return $this->makeGetRequest($url, $params);
    }
}
