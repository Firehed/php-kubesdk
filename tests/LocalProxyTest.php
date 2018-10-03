<?php
declare(strict_types=1);

namespace Kubernetes;

/**
 * @coversDefaultClass Kubernetes\LocalProxy
 * @covers ::<protected>
 * @covers ::<private>
 */
class LocalProxyTest extends \PHPUnit\Framework\TestCase
{
    /** @covers ::__construct */
    public function testConstruct()
    {
        $api = new LocalProxy('http://localhost:8001');
        $this->assertInstanceOf(Api::class, $api);
    }
}
