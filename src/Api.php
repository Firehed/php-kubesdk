<?php
declare(strict_types=1);

namespace Kubernetes;

interface Api
{
    public function get(string $endpoint): array;
}
