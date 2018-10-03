<?php
declare(strict_types=1);

namespace Kubernetes;

use RuntimeException;

/**
 * @coversDefaultClass Kubernetes\ServiceAccount
 * @covers ::<protected>
 * @covers ::<private>
 */
class ServiceAccountTest extends \PHPUnit\Framework\TestCase
{
    private $secretDir;

    public function setUp()
    {
        $tmp = sys_get_temp_dir();
        $this->secretDir = $tmp . '/kubesdk-test';
        if (!file_exists($this->secretDir)) {
            mkdir($this->secretDir, 0755);
        }
        $token = file_put_contents($this->secretDir.'/token', 'some token');
        $ca = file_put_contents($this->secretDir.'/ca.crt', 'some ca cert');
    }

    public function tearDown()
    {
        unlink($this->secretDir.'/token');
        unlink($this->secretDir.'/ca.crt');
        rmdir($this->secretDir);
    }

    /** @covers ::__construct */
    public function testConstructThrowsIfTokenIsUnreadable()
    {
        file_put_contents($this->secretDir.'/token', '');
        $this->expectException(RuntimeException::class);
        new ServiceAccount($this->secretDir);
    }

    /** @covers ::__construct */
    public function testConstructThrowsIfEnvNotDefined()
    {
        $this->expectException(RuntimeException::class);
        new ServiceAccount($this->secretDir);
    }

    /** @covers ::__construct */
    public function testConstructWhenEnvDefined()
    {
        putenv('KUBERNETES_SERVICE_HOST=10.0.0.1');
        putenv('KUBERNETES_SERVICE_PORT=443');
        $api = new ServiceAccount($this->secretDir);
        $this->assertInstanceOf(Api::class, $api);
    }
}
