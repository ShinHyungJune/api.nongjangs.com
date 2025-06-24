<?php

namespace Tests\Feature;

use App\Services\Tracker;
use Tests\TestCase;

class TrackersTest extends TestCase
{
    protected $tracker;
    protected $trackingNumber;
    protected $carrierId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tracker = new Tracker();

        // 테스트용 운송장번호와 택배사 고유번호 설정
        $this->trackingNumber = "251955303040";
        $this->carrierId = "kr.lotte"; // CJ대한통운
    }

    /** @test */
    public function 운송장번호와_택배사_고유번호로_배송상태를_조회할_수_있다()
    {
        $result = $this->tracker->getState($this->trackingNumber, $this->carrierId);

        $this->assertEquals(true, $result['success']);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('finish', $result['data']);
    }

    /** @test */
    public function 택배사목록을_호출할_수_있다()
    {
        $result = $this->tracker->getCarriers();

        $this->assertEquals(true, $result['success']);

        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);

        if (count($result['data']) > 0) {
            $carrier = $result['data'][0];
            $this->assertArrayHasKey('title', $carrier);
            $this->assertArrayHasKey('code', $carrier);
        }
    }
}
