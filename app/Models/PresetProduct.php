<?php

namespace App\Models;

use App\Enums\DeliveryCompany;
use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Enums\StatePrototype;
use App\Enums\TypeAlarm;
use App\Enums\TypePointHistory;
use App\Mail\PrototypeNeeded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PresetProduct extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $table = 'preset_product';
    public static $confirmPointRatio = 2; // 구매확정 포인트 제공비율

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::creating(function ($model){
            $model->uuid = Generator::createUuid($model);
        });

        self::updating(function ($model){
            $prevDeliveryNumber = $model->getOriginal('delivery_number');
            $prevDeliveryAt = $model->getOriginal('delivery_at');


            if(!$prevDeliveryNumber && $model->delivery_number) {
                $model->state = StatePresetProduct::ONGOING_DELIVERY;

                $model->need_alert_delivery = 1;
            }

            if(!$prevDeliveryAt && $model->delivery_at)
                $model->state = StatePresetProduct::DELIVERED;
        });

        self::updated(function ($model){
            $prevSubmitRequest = $model->getOriginal('submit_request');
            $prevState = $model->getOriginal('state');

            // 주문 후 나중에 시안작성완료했을 경우
            if(!$prevSubmitRequest && $model->submit_request && $model->preset->order && $model->preset->order->state == StateOrder::SUCCESS) {
                Alarm::create([
                    'contact' => env('ADMIN_CONTACT', '01092106861'),
                    'preset_product_id' => $model->id,
                    'type' => TypeAlarm::PRESET_PRODUCT_PROTOTYPE_REQUIRED,
                ]);
                // Mail::to(env('MAIL_ADMIN_ADDRESS', 'janginbiz@naver.com'))->send(new PrototypeNeeded($model));
            }

            // 구매확정
            if($prevState != StatePresetProduct::CONFIRMED && $model->state == StatePresetProduct::CONFIRMED){
                $preset = $model->preset;

                $user = User::withTrashed()->find($preset->user_id);

                if($user){
                    $point = floor($model->price_total / 100 * self::$confirmPointRatio);

                    $user->update(['point' => $user->point + $point]);

                    $user->pointHistories()->create([
                        'point_current' => $user->point,
                        'point' => $point,
                        'type' => TypePointHistory::PRESET_PRODUCT_CONFIRMED,
                        'increase' => 1,
                    ]);
                }
            }
        });
    }

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('sheet')->singleFile();
        // $this->addMediaCollection('logo')->singleFile();
        // $this->addMediaCollection('stamp')->singleFile();
    }
    public function getFilesAttribute()
    {
        $medias = $this->getMedia("files");

        $items = [];

        foreach($medias as $media){
            $items[] = [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return $items;
    }
    public function getSheetAttribute()
    {
        if($this->hasMedia('sheet')) {
            $media = $this->getMedia('sheet')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }
    /*public function getLogoAttribute()
    {
        if($this->hasMedia('logo')) {
            $media = $this->getMedia('logo')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }
    public function getStampAttribute()
    {
        if($this->hasMedia('stamp')) {
            $media = $this->getMedia('stamp')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }*/

    public function preset(): BelongsTo
    {
        return $this->belongsTo(Preset::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function getDeliveryUrlAttribute()
    {
        // 주요 택배사 2~3개만 준비해주면 될듯
        $url = "";

        if($this->delivery_company == DeliveryCompany::CJ)
            $url = "https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no={$this->delivery_number}";

        if($this->delivery_company == DeliveryCompany::LOGEN)
            $url = "https://www.ilogen.com/web/personal/trace/{$this->delivery_number}";

        if($this->delivery_company == DeliveryCompany::LOTTE)
            $url = "https://www.lotteglogis.com/home/reservation/tracking/linkView?InvNo={$this->delivery_number}";

        if($this->delivery_company == DeliveryCompany::POST)
            $url = "https://service.epost.go.kr/trace.RetrieveDomRigiTraceList.comm?sid1={$this->delivery_number}&displayHeader=";

        return $url;
    }

    public function getCanRefundAttribute()
    {
        if(!auth()->user())
            return 0;

        if(auth()->user()->id != $this->preset->user_id)
            return 0;

        if($this->state != StatePresetProduct::DELIVERED)
            return 0;

        return 1;
    }

    public function getConfirmPrototypeAttribute()
    {
       return  $this->prototypes()->where('confirmed', 1)->count() > 0 ? 1 : 0;
    }

    public function getCanConfirmAttribute()
    {
        if(!auth()->user())
            return 0;

        if(auth()->user()->id != $this->preset->user_id)
            return 0;

        if($this->state != StatePresetProduct::DELIVERED)
            return 0;

        return 1;
    }

    public function getAdminCanConfirmAttribute()
    {
        if(!auth()->user())
            return 0;

        if(!auth()->user()->admin)
            return 0;

        if(in_array($this->state, [
            StatePresetProduct::BEFORE_PAYMENT,
            StatePresetProduct::CANCEL,
            StatePresetProduct::ONGOING_REFUND,
            StatePresetProduct::FINISH_REFUND,
            StatePresetProduct::CONFIRMED,
        ])) {
            return 0;
        }

        return 1;
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getCanReviewAttribute()
    {
        if(!auth()->user())
            return 0;

        if(auth()->user()->id != $this->preset->user_id)
            return 0;

        if($this->state != StatePresetProduct::DELIVERED && $this->state != StatePresetProduct::CONFIRMED)
            return 0;

        if($this->review)
            return 0;

        return 1;
    }

    public function getUrlReorderAttribute()
    {
        $product = Product::withTrashed()->find($this->product_id);

        if($product)
            return "/products/".$product->id;

        return "/";
    }

    public function getCanOrderAttribute()
    {
        if($this->state != StatePresetProduct::BEFORE_PAYMENT)
            return 0;

        if(auth()->user() && (auth()->user()->id != $this->preset->user_id))
            return 0;

        $product = Product::withTrashed()->find($this->product_id);
        $size = Size::find($this->size_id);
        $color = Color::find($this->color_id);

        if(!$product->open)
            return 0;

        if($product->empty)
            return 0;

        if($size && !$size->open)
            return 0;

        if($color && !$color->open)
            return 0;

        return 1;
    }

    public function getPriceTotalAttribute()
    {
        if($this->additional)
            return $this->price * $this->count;

        return ($this->price + $this->size_price) * $this->count;
    }

    public function prototypes()
    {
        return $this->hasMany(Prototype::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function getStatePrototypeAttribute()
    {
        $prototypes = $this->prototypes();

        if(!$this->submit_request)
            return StatePrototype::EMPTY;

        if(!$this->will_prototype_finished_at)
            return StatePrototype::WAIT;

        if($this->prototypes()->count() == 0)
            return StatePrototype::ONGOING;

        if($this->prototypes()->where('confirmed', 1)->count() == 0)
            return StatePrototype::FINISH;

        if($this->prototypes()->where('confirmed', 1)->count() > 0)
            return StatePrototype::CONFIRM;

        return null;
    }

    public function getHasUncheckFeedbackAttribute()
    {
        return $this->feedbacks()->where('check', 0)->first() ? 1 : 0;
    }

    public function getPresentAttribute()
    {
        $order = $this->preset->order;

        if($order)
            return $order->presentPresetProduct->id == $this->id ? 1: 0;

        return 0;
        // return $this->preset->presetProducts()->where('additional', 0)->orderBy('id', 'asc')->first()->id == $this->id ? 1: 0;
    }

    public function checkDeliveryState()
    {
        // CJ택배
        if($this->delivery_company == DeliveryCompany::CJ) {
            $response = Http::get("https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no={$this->delivery_number}");

            if (str_contains($response->body(), "고객님의 상품이 배달완료 되었습니다."))
                $this->update(["state" => StatePresetProduct::DELIVERED]);
        }

        // 롯데
        if($this->delivery_company == DeliveryCompany::LOTTE) {
            $response = Http::get("https://www.lotteglogis.com/mobile/reservation/tracking/linkView?InvNo={$this->delivery_number}");

            if (str_contains($response->body(), "배달 완료하였습니다."))
                $this->update(["state" => StatePresetProduct::DELIVERED]);
        }

        // 우체국택배
        if($this->delivery_company == DeliveryCompany::POST) {
            $response = Http::get("https://service.epost.go.kr/trace.RetrieveDomRigiTraceList.comm?sid1={$this->delivery_number}&displayHeader=");

            if (str_contains($response->body(), "배달완료 ( 배달 )"))
                $this->update(["state" => StatePresetProduct::DELIVERED]);

        }

        // 로젠택배
        if($this->delivery_company == DeliveryCompany::LOGEN) {
            $response = Http::get("https://www.ilogen.com/web/personal/trace/{$this->delivery_number}");

            if(str_contains($response->body(), "고객님께 물품을 전달하였습니다."))
                $this->update(["state" => StatePresetProduct::DELIVERED]);

            return $this;
        }

        // 한진
        /*if($this->delivery_company == DeliveryCompany::HANJIN){
            $response = Http::get("https://apis.tracker.delivery/carriers/kr.hanjin/tracks/{$this->delivery_number}");

            if(str_contains($response->body(), "배송완료"))
                $this->update(["state" => StatePresetProduct::DELIVERED]);

            return $this;
        }*/
    }

    public function logo()
    {
        return $this->belongsTo(Logo::class);
    }
}
