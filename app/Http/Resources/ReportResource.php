<?php

namespace App\Http\Resources;

use App\Enums\StateReport;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\User;
use App\Models\VegetableStory;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Report */
class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        $reportable = null;
        $targetUser = null;
        $title = "";
        $formatType = "";

        $user = User::withTrashed()->find($this->user_id);

        if($this->reportable_type == Product::class) {
            $reportable = ProductResource::make($this->reportable);
            $formatType = "상품";
            $title = $this->reportable->title;
        }
        if($this->reportable_type == Review::class) {
            $reportable = ReviewResource::make($this->reportable);

            $targetUser = $this->reportable->user;

            $formatType = "리뷰";
            $title = $this->reportable->description;
        }

        if($this->reportable_type == Comment::class) {
            $reportable = CommentResource::make($this->reportable);

            $targetUser = $this->reportable->user;

            $formatType = "댓글";
            $title = $this->reportable->description;
        }
        if($this->reportable_type == VegetableStory::class) {
            $reportable = VegetableStoryResource::make($this->reportable);

            $targetUser = $this->reportable->user;
            $formatType = "채소이야기";
            $title = $this->reportable->description;
        }
        if($this->reportable_type == Recipe::class) {
            $reportable = RecipeResource::make($this->reportable);

            $targetUser = $this->reportable->user;

            $formatType = "레시피";
            $title = $this->reportable->description;
        }

        return [
            'id' => $this->id,
            'user' => $user ? [
                'id' => $user->id,
                'nickname' => $user->nickname,
                'name' => $user->name,
            ] : '',
            'targetUser' => $targetUser ? [
                'id' => $targetUser->id,
                'nickname' => $targetUser->nickname,
                'name' => $targetUser->name,
            ] : '',
            // 'reportable' => $reportable,
            'format_type' => $formatType,
            'title' => $title,
            'state' => $this->state,
            'format_state' => StateReport::getLabel($this->state),

            'description' => $this->description,

            'report_category_id' => $this->report_category_id,

            'reportCategory' => ReportCategoryResource::make($this->reportCategory),
            'created_at' => $this->created_at,
            'format_created_at' => Carbon::make($this->created_at)->format('Y.m.d H:i')
        ];
    }
}
