<?php

namespace App\Http\Resources;

use App\Models\Recipe;
use App\Models\VegetableStory;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Bookmark */
class BookmarkResource extends JsonResource
{
    public function toArray($request)
    {
        $bookmarkable = "";

        if($this->bookmarkable_type == Recipe::class)
            $bookmarkable = RecipeResource::make($this->bookmarkable);

        if($this->bookmarkable_type == VegetableStory::class)
            $bookmarkable = VegetableStoryResource::make($this->bookmarkable);

        return [
            'id' => $this->id,
            'bookmarkable' => $bookmarkable,
            'bookmarkable_id' => $this->bookmarkable_id,
            'bookmarkable_type' => $this->bookmarkable_type,
        ];
    }
}
