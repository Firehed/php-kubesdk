<?php
declare(strict_types=1);

namespace Kubernetes;

use RuntimeException;

trait CurlTrait
{
    protected function makeGetRequest(string $url, array $extraOpts = []): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s%s', $this->baseUrl, $url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, sprintf('php-kubesdk/%s', Api::VERSION));
        curl_setopt_array($ch, $extraOpts);

        $ret = curl_exec($ch);

        if ($ret === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            if ($errno === CURLE_OPERATION_TIMEOUTED) {
                throw new RuntimeException('Timeout while using Kubernetes API');
            }
            throw new RuntimeException(sprintf(
                'Error while interacting with Kubernetes API: [%d] %s',
                $errno,
                $error
            ));
        }
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
