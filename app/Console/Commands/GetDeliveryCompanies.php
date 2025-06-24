<?php

namespace App\Console\Commands;

use App\Models\DeliveryCompany;
use App\Services\Tracker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetDeliveryCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:delivery-companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '택배사 목록을 DeliveryCompany 모델에 저장합니다';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('택배사 목록 동기화를 시작합니다...');

        $tracker = new Tracker();
        $carriersResponse = $tracker->getCarriers();

        if (!$carriersResponse['success']) {
            $this->error($carriersResponse['message']);
            Log::error('택배사 목록 동기화 실패: ' . $carriersResponse['message']);
            return 1;
        }

        $carriers = $carriersResponse['data'];

        if (empty($carriers)) {
            $this->warn('동기화할 택배사 목록이 없습니다.');
            return 0;
        }

        $count = 0;
        foreach ($carriers as $carrier) {
            DeliveryCompany::updateOrCreate(
                [
                    'code' => $carrier['code']
                ],
                [
                    'code' => $carrier['code'],
                    'title' => $carrier['title'],
                ]
            );
            $count++;
        }

        $this->info("총 {$count}개의 택배사 정보를 동기화했습니다.");

        return 0;
    }
}
