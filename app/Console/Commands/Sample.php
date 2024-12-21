<?php

namespace App\Console\Commands;

use App\Enums\State;
use App\Enums\StateCampaign;
use App\Enums\TypeAlarm;
use App\Enums\TypePointHistory;
use App\Http\Resources\WebsiteReservationResource;
use App\Models\Alarm;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Campaign;
use App\Models\Like;
use App\Models\PointHistory;
use App\Models\Program;
use App\Models\User;
use App\Models\Waiting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Sample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:alarms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '알림';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 등록마감일
        $items = Campaign::where("state", StateCampaign::ACCEPT)
            ->where("application_finished_at", ">=", Carbon::now()->startOfDay())
            ->where("application_finished_at", "<=", Carbon::now()->endOfDay())
            ->cursor();

        foreach($items as $item){
            $likes = Like::where("likeable_type", Campaign::class)->where("likeable_id", $item->id)->curosr();

            foreach($likes as $like){
                Alarm::create([
                    "user_id" => $like->user_id,
                    "type" => TypeAlarm::CAMPAIGN_APPLICATION_D_DAY,
                    "campaign_id" => $item->id,
                ]);
            }
        }

        // 리뷰마감일
        $items = Application::where("selected", 1)->whereHas("campaign", function ($query){
            $query->where("review_finished_at", ">=", Carbon::now()->startOfDay())
                ->where("review_finished_at", "<=", Carbon::now()->endOfDay());
        })->cursor();

        foreach($items as $item){
            Alarm::create([
                "user_id" => $item->user_id,
                "type" => TypeAlarm::APPLICATION_REVIEW_D_DAY,
                "application_id" => $item->id,
            ]);
        }

        // 선정 D-DAY
        $items = Campaign::where("state", StateCampaign::ACCEPT)
            ->where("select_opened_at", ">=", Carbon::now()->startOfDay())
            ->where("select_opened_at", "<=", Carbon::now()->endOfDay())
            ->cursor();

        foreach($items as $item){
            Alarm::create([
                "user_id" => $item->user_id,
                "type" => TypeAlarm::CAMPAIGN_SELECT_D_DAY,
                "campaign_id" => $item->id,
            ]);
        }

        // 선정 1일전
        $items = Campaign::where("state", StateCampaign::ACCEPT)
            ->where("select_opened_at", ">=", Carbon::now()->subDay()->startOfDay())
            ->where("select_opened_at", "<=", Carbon::now()->subDay()->endOfDay())
            ->cursor();

        foreach($items as $item){
            Alarm::create([
                "user_id" => $item->user_id,
                "type" => TypeAlarm::CAMPAIGN_SELECT_D_1,
                "campaign_id" => $item->id,
            ]);
        }

        // 발표 D-DAY
        $items = Campaign::where("state", StateCampaign::ACCEPT)
            ->where("winner_at", ">=", Carbon::now()->startOfDay())
            ->where("winner_at", "<=", Carbon::now()->endOfDay())
            ->cursor();

        foreach($items as $item){
            // 광고주 발송
            Alarm::create([
                "user_id" => $item->user_id,
                "type" => TypeAlarm::CAMPAIGN_WINNER_D_DAY,
                "campaign_id" => $item->id,
            ]);

            // 인플루언서 발송
            $applications = $item->applications()->where("selected", 1)->get();

            foreach($applications as $application){
                Alarm::create([
                    "user_id" => $application->user_id,
                    "type" => TypeAlarm::CAMPAIGN_WINNER_D_DAY_FOR_INFLUENCER,
                    "application_id" => $application->id,
                ]);
            }
        }

        // 발표 1일전
        $items = Campaign::where("state", StateCampaign::ACCEPT)
            ->where("winner_at", ">=", Carbon::now()->subDay()->startOfDay())
            ->where("winner_at", "<=", Carbon::now()->subDay()->endOfDay())
            ->cursor();

        foreach($items as $item){
            Alarm::create([
                "user_id" => $item->user_id,
                "type" => TypeAlarm::CAMPAIGN_WINNER_D_1,
                "campaign_id" => $item->id,
            ]);
        }
    }
}
