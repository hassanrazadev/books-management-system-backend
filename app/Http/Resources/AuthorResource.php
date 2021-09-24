<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AuthorResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request) {
        $author = parent::toArray($request);
        $author['created_at'] = Carbon::parse($author['created_at'])->format('Y-m-d');
        $author['updated_at'] = Carbon::parse($author['updated_at'])->format('Y-m-d');
        unset($author['media']);
        return  $author;
    }
}
