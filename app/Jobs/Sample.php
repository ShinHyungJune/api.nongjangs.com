<?php

namespace App\Jobs;

use App\Enums\CategoryAlarm;
use App\Enums\TypeAlarm;
use App\Models\Alarm;
use App\Models\Keyword;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class KeywordAlarmJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $keyword;
    protected $product;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Keyword $keyword, Product $product)
    {
        $this->keyword = $keyword;
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = $this->keyword->users()->cursor();

        foreach($users as $user){
            Alarm::create([
                "user_id" => $user->id,
                "type" => TypeAlarm::PRODUCT_INTEREST_KEYWORD_CREATED,
                "category" => CategoryAlarm::KEYWORD,
                "product_id" => $this->product->id,
                "keyword_id" => $this->keyword->id,
            ]);
        }
    }
}
