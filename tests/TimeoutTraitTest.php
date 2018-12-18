<?php
declare(strict_types=1);

namespace Kubernetes;

/**
 * @coversDefaultClass Kubernetes\TimeoutTrait
 * @covers ::<protected>
 * @covers ::<private>
 */
class TimeoutTraitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers ::getCurlTimeoutOptions
     */
    public function testDefaultCurlValues()
    {
        $class = $this->getInstance();
        $curlValues = $class->getCurlTimeoutOptions();
        $this->assertArrayHasKey(CURLOPT_TIMEOUT_MS, $curlValues);
        $this->assertSame(
            Api::DEFAULT_TIMEOUT_MS,
            $curlValues[CURLOPT_TIMEOUT_MS],
            'Wrong default timeout'
        );
        $this->assertArrayHasKey(CURLOPT_CONNECTTIMEOUT_MS, $curlValues);
        $this->assertSame(
            Api::DEFAULT_CONNECT_TIMEOUT_MS,
            $curlValues[CURLOPT_CONNECTTIMEOUT_MS],
            'Wrong default connect timeout'
        );
    }

    /**
     * @covers ::setTimeoutMs
     * @covers ::setConnectTimeoutMs
     */
    public function testSettingNewValuesApplies()
    {
        $class = $this->getInstance();
        $class->setTimeoutMs(1234);
        $class->setConnectTimeoutMs(4321);
        $curlValues = $class->getCurlTimeoutOptions();

        $this->assertArrayHasKey(CURLOPT_TIMEOUT_MS, $curlValues);
        $this->assertSame(
            1234,
            $curlValues[CURLOPT_TIMEOUT_MS],
            'Wrong timeout'
        );
        $this->assertArrayHasKey(CURLOPT_CONNECTTIMEOUT_MS, $curlValues);
        $this->assertSame(
            4321,
            $curlValues[CURLOPT_CONNECTTIMEOUT_MS],
            'Wrong connect timeout'
        );
    }

    private function getInstance()
    {
        return new class {
            use TimeoutTrait {
                getCurlTimeoutOptions as public;
            }
        };
    }
}
