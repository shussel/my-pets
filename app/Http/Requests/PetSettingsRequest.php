<?php

namespace App\Http\Requests;

use App\Enums\ChipRegistryEnum;
use App\Enums\CollarColorEnum;
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
            'food' => 'sometimes|array:feed,time_1,time_2,interval|nullable',
            'food.feed' => ['sometimes', Rule::enum(FeedEnum::class)],
            'food.time_1' => 'sometimes|nullable|date_format:H:i|required_unless:food.feed,free',
            'food.time_2' => 'sometimes|nullable|date_format:H:i|required_if:food.feed,twice,multi',
            'food.interval' => 'sometimes|integer|nullable|min:1|max:8|required_if:food.feed,multi',
            'poop' => 'sometimes|array:location,time_1,time_2,interval,door|nullable',
            'poop.location' => ['sometimes', Rule::enum(PoopEnum::class)],
            'poop.time_1' => 'sometimes|nullable|date_format:H:i',
            'poop.time_2' => 'sometimes|nullable|date_format:H:i',
            'poop.interval' => 'sometimes|integer|nullable|min:0|max:90',
            'poop.door' => 'sometimes|boolean|nullable',
            'sleep' => 'sometimes|array:location,time_1,time_2,assist|nullable',
            'sleep.location' => ['sometimes', Rule::enum(SleepEnum::class)],
            'sleep.time_1' => 'sometimes|nullable|date_format:H:i',
            'sleep.time_2' => 'sometimes|nullable|date_format:H:i',
            'sleep.assist' => 'sometimes|boolean|nullable',
            'behavior' => 'sometimes|array:activity,sociability,intelligence,noise|nullable',
            'behavior.activity' => ['sometimes', Rule::enum(ActivityEnum::class)],
            'behavior.sociability' => ['sometimes', Rule::enum(SociabilityEnum::class)],
            'behavior.intelligence' => ['sometimes', Rule::enum(IntelligenceEnum::class)],
            'behavior.noise' => ['sometimes', Rule::enum(NoiseEnum::class)],
            'identity' => 'sometimes|array:collar,chipped,registry,chip_id,marks|nullable',
            'identity.collar' => ['sometimes', Rule::enum(CollarColorEnum::class)],
            'identity.chipped' => 'sometimes|boolean|nullable',
            'identity.registry' => ['sometimes', Rule::enum(ChipRegistryEnum::class)],
            'identity.chip_id' => 'sometimes|string|nullable|alpha_num|min:9|max:15',
            'identity.marks' => 'sometimes|string|nullable|max:100',
        ];
    }
}
