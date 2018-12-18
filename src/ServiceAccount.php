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
    use CurlTrait;
    use TimeoutTrait;

    private const DEFAULT_SECRETS_DIRECTORY = '/var/run/secrets/kubernetes.io/serviceaccount';

    /** @var string */
    private $baseUrl;

    /** @var string */
    private $caCert;

    /** @var string */
    private $token;

    public function __construct(string $secretsDir = self::DEFAULT_SECRETS_DIRECTORY)
    {
        $tokenFile = sprintf('%s/token', $secretsDir);
        $token = file_get_contents($tokenFile);
        if (!$token) {
            throw new RuntimeException('Could not read service account token');
        }
        $this->token = trim($token);

        $this->caCert = sprintf('%s/ca.crt', $secretsDir);

        $host = getenv('KUBERNETES_SERVICE_HOST');
        $port = getenv('KUBERNETES_SERVICE_PORT');

        if (!$host || !$port) {
            throw new RuntimeException('KUBERNETES_SERVICE_HOST or KUBERNETES_SERVICE_PORT env not defined');
        }

        $this->baseUrl = sprintf('https://%s:%d', $host, $port);
    }

    public function get(string $url): array
    {
        $params = [
            CURLOPT_CAINFO => $this->caCert,
            CURLOPT_HTTPHEADER => [
                sprintf('Authorization: Bearer %s', $this->token),
            ],
        ] + $this->getCurlTimeoutOptions();

        return $this->makeGetRequest($url, $params);
    }
}
