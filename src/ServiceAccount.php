<?php
declare(strict_types=1);

namespace Kubernetes;

use RuntimeException;

/**
 * This class interacts with the Kubernetes API using the values that should be
 * provided automatically to any Pod. Note that the API interactions will still
 * be limited by the Pod's ServiceAccount and its associated Roles and
 * RoleBindings (and Cluster equivalents).
 */
class ServiceAccount implements Api
{
    private const SECRETS_DIRECTORY = '/var/run/secrets/kubernetes.io/serviceaccount';

    /** @var string */
    private $baseUrl;

    /** @var string */
    private $caCert;

    /** @var string */
    private $token;

    public function __construct()
    {
        $tokenFile = sprintf('%s/token', self::SECRETS_DIRECTORY);
        $token = file_get_contents($tokenFile);
        if (!$token) {
            throw new RuntimeException('Could not read service account token');
        }
        $this->token = trim($token);

        $this->caCert = sprintf('%s/ca.crt', self::SECRETS_DIRECTORY);

        $host = getenv('KUBERNETES_SERVICE_HOST');
        $port = getenv('KUBERNETES_SERVICE_PORT');

        if (!$host || !$port) {
            throw new RuntimeException('KUBERNETES_SERVICE_HOST or KUBERNETES_SERVICE_PORT env not defined');
        }

        $this->baseUrl = sprintf('https://%s:%d', $host, $port);
    }

    public function get(string $url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s%s', $this->baseUrl, $url));
        curl_setopt($ch, CURLOPT_CAINFO, $this->caCert);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: Bearer %s', $this->token),
        ]);
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
