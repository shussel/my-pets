<?php

namespace App\Http\Requests;

use App\Enums\ChipRegistryEnum;
use App\Enums\CollarColorEnum;
use App\Enums\FeedEnum;
use App\Enums\GpsProviderEnum;
use App\Enums\PoopEnum;
use App\Enums\SleepEnum;
use App\Enums\ActivityEnum;
use App\Enums\SociabilityEnum;
use App\Enums\IntelligenceEnum;
use App\Enums\NoiseEnum;
use App\Enums\IntervalEnum;
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
            'schedule' => 'sometimes|array:category,act,time1,time2,interval,unit,daily,toggle,last|nullable',
            'schedule.category' => 'sometimes|string|nullable',
            'schedule.act' => 'sometimes|array|nullable',
            'schedule.time1' => 'sometimes|nullable|date_format:H:i',
            'schedule.time2' => 'sometimes|nullable|date_format:H:i',
            'schedule.interval' => 'sometimes|integer|nullable|min:1|max:90|required_with:unit',
            'schedule.unit' => ['sometimes', 'nullable', Rule::enum(IntervalEnum::class), 'required_with:interval'],
            'schedule.daily' => 'sometimes|boolean|nullable',
            'schedule.toggle' => 'sometimes|boolean|nullable',
            'schedule.last' => 'sometimes|date|nullable',
            'food' => 'sometimes|array:feed|nullable',
            'food.feed' => ['sometimes', Rule::enum(FeedEnum::class)],

            'poop' => 'sometimes|array:location,time_1,time_2,interval,assist,last|nullable',
            'poop.location' => ['sometimes', Rule::enum(PoopEnum::class)],
            'poop.time_1' => 'sometimes|nullable|date_format:H:i',
            'poop.time_2' => 'sometimes|nullable|date_format:H:i',
            'poop.interval' => 'sometimes|integer|nullable|min:0|max:90',
            'poop.assist' => 'sometimes|boolean|nullable',
            'poop.last' => 'sometimes|date|nullable',
            'sleep' => 'sometimes|array:location,time_1,time_2,last,assist|nullable',
            'sleep.location' => ['sometimes', Rule::enum(SleepEnum::class)],
            'sleep.time_1' => 'sometimes|nullable|date_format:H:i',
            'sleep.time_2' => 'sometimes|nullable|date_format:H:i',
            'sleep.assist' => 'sometimes|boolean|nullable',
            'sleep.last' => 'sometimes|date|nullable',
            'behavior' => 'sometimes|array:activity,sociability,intelligence,noise|nullable',
            'behavior.activity' => ['sometimes', Rule::enum(ActivityEnum::class)],
            'behavior.sociability' => ['sometimes', Rule::enum(SociabilityEnum::class)],
            'behavior.intelligence' => ['sometimes', Rule::enum(IntelligenceEnum::class)],
            'behavior.noise' => ['sometimes', Rule::enum(NoiseEnum::class)],
            'identity' => 'sometimes|array:collar,tags,chipped,registry,chip_id,gps,gps_provider,marks|nullable',
            'identity.collar' => ['sometimes', Rule::enum(CollarColorEnum::class)],
            'identity.tags' => 'sometimes|boolean|nullable',
            'identity.chipped' => 'sometimes|boolean|nullable',
            'identity.registry' => ['sometimes', Rule::enum(ChipRegistryEnum::class)],
            'identity.chip_id' => 'sometimes|string|nullable|alpha_num|min:9|max:15',
            'identity.gps' => 'sometimes|boolean|nullable',
            'identity.gps_provider' => ['sometimes', Rule::enum(GpsProviderEnum::class)],
            'identity.marks' => 'sometimes|string|nullable|max:100',
        ];
    }
}
