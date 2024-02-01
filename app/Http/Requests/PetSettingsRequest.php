<?php

namespace App\Http\Requests;

use App\Enums\FeedEnum;
use App\Enums\PoopEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'food' => 'sometimes|array|nullable',
            'food.feed' => ['sometimes', Rule::enum(FeedEnum::class)],
            'food.feed_time_1' => 'sometimes',
            'food.feed_time_2' => 'sometimes',
            'food.feed_interval' => 'sometimes',
            'poop' => 'sometimes|array|nullable',
            'poop.location' => ['sometimes', Rule::enum(PoopEnum::class)],
            'poop.poop_time_1' => 'sometimes',
            'poop.poop_time_2' => 'sometimes',
            'poop.clean_interval' => 'sometimes',
            'poop.pet_door' => 'sometimes',
        ];
    }
}
