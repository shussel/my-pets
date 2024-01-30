<?php

namespace App\Http\Requests;

use App\Enums\FeedEnum;
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
        ];
    }
}
