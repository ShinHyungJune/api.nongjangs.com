<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function sync($model, $string)
    {
        $model->keywords()->sync([]);

        if($string) {
            $parts = explode(',', $string);

            foreach ($parts as $part) {
                // 점으로 분할하여 공백 포함 부분을 분리
                $keywords = preg_split('/\s*\.\s*/', $part);

                foreach ($keywords as $keyword) {
                    $foundKeyword = Keyword::where("title", $keyword)->first();

                    if(!$foundKeyword)
                        $foundKeyword = Keyword::create([
                            "title" => $keyword,
                        ]);

                    $model->keywords()->attach($foundKeyword->id);
                }
            }
        }
    }

    public static function validate($string, $maxPerWord = 15, $maxCount = 5)
    {
        if(!$string)
            return [
                "success" => 1,
            ];

        $items = explode(',', $string);

        if(count($items) > $maxCount)
            return [
                "success" => 0,
                "message" => "최대 {$maxCount}개까지만 등록 가능합니다."
            ];

        foreach($items as $item){
            if(strlen($item) > $maxPerWord)
                return [
                    "success" => 0,
                    "message" => "단어 '{$item}' 길이가 {$maxPerWord}자를 초과합니다."
                ];
        }

        return [
            "success" => 1,
        ];
    }
}
