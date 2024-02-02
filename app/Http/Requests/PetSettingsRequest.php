<?php

namespace App\Http\Requests;

use App\Enums\FeedEnum;
use App\Enums\PoopEnum;
use App\Enums\SleepEnum;
use App\Enums\ActivityEnum;
use App\Enums\SociabilityEnum;
use App\Enums\IntelligenceEnum;
use App\Enums\NoiseEnum;
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
            'food.time_1' => 'sometimes',
            'food.time_2' => 'sometimes',
            'food.interval' => 'sometimes',
            'poop' => 'sometimes|array|nullable',
            'poop.location' => ['sometimes', Rule::enum(PoopEnum::class)],
            'poop.time_1' => 'sometimes',
            'poop.time_2' => 'sometimes',
            'poop.interval' => 'sometimes',
            'poop.door' => 'sometimes',
            'sleep' => 'sometimes|array|nullable',
            'sleep.location' => ['sometimes', Rule::enum(SleepEnum::class)],
            'sleep.time_1' => 'sometimes',
            'sleep.time_2' => 'sometimes',
            'sleep.assist' => 'sometimes',
            'behavior' => 'sometimes|array|nullable',
            'behavior.activity' => ['sometimes', Rule::enum(ActivityEnum::class)],
            'behavior.sociability' => ['sometimes', Rule::enum(SociabilityEnum::class)],
            'behavior.intelligence' => ['sometimes', Rule::enum(IntelligenceEnum::class)],
            'behavior.noise' => ['sometimes', Rule::enum(NoiseEnum::class)],
        ];
    }
}
