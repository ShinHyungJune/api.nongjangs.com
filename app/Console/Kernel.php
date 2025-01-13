<?php

namespace App\Console;

use App\Console\Commands\AlertDeliveryStart;
use App\Console\Commands\AlertReviewNeed;
use App\Console\Commands\AlertWaitPrototype;
use App\Console\Commands\CancelCampaigns;
use App\Console\Commands\CheckBirthday;
use App\Console\Commands\CheckDeliveryState;
use App\Console\Commands\ConfirmPresets;
use App\Console\Commands\GetSupportBusiness;
use App\Console\Commands\Sample;
use App\Console\Commands\CreateApplicationHistory;
use App\Console\Commands\GetApplicationReaction;
use App\Console\Commands\RemoveOldPoint;
use App\Jobs\CheckDeliveryStateJob;
use App\Models\SupportBusiness;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ConfirmPresets::class,
        AlertDeliveryStart::class,
        AlertReviewNeed::class,
        CheckDeliveryState::class,
        AlertWaitPrototype::class,
        CheckBirthday::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
