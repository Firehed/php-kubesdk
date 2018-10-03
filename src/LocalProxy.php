<?php
declare(strict_types=1);

namespace Kubernetes;

use RuntimeException;

class LocalProxy implements Api
{
    /** @var string */
    private $baseUrl;

    public function __construct(string $url)
    {
        $this->baseUrl = $url;
    }

    public function get(string $url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s%s', $this->baseUrl, $url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        curl_close($ch);
        if (!is_string($ret)) {
            throw new RuntimeException('No data returned from API');
        }
        $data = json_decode($ret, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(json_last_error_msg());
        }
        return $data;
    }
}
