<?php

namespace App\Http\Requests;

use App\Enums\ChipRegistryEnum;
use App\Enums\CollarColorEnum;
use App\Enums\FeedEnum;
use App\Enums\GpsProviderEnum;
use App\Enums\PoopEnum;
use App\Enums\RepeatsEnum;
use App\Enums\IntervalEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleRequest extends FormRequest
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
            'category' => 'sometimes|string|nullable',
            'schedule' => 'required|array',
            'schedule.*._id' => 'required|string',
            'schedule.*.category' => 'sometimes|string|nullable',
            'schedule.*.action' => 'sometimes|string|nullable',
            'schedule.*.with' => 'sometimes|string|nullable',
            'schedule.*.location' => 'sometimes|string|nullable',
            'schedule.*.startDate' => 'sometimes|date|nullable',
            'schedule.*.repeat' => ['sometimes', 'nullable', Rule::enum(RepeatsEnum::class), 'required_with:interval'],
            'schedule.*.count' => 'sometimes|integer|nullable|min:1|max:100|required_with:interval',
            'schedule.*.interval' => ['sometimes', 'nullable', Rule::enum(IntervalEnum::class)],
            'schedule.*.dow' => 'sometimes|array',
            'schedule.*.startTime' => 'sometimes|nullable|date_format:H:i',
            'schedule.*.endTime' => 'sometimes|nullable|date_format:H:i',
            'schedule.*.times' => 'sometimes|array',
            'schedule.*.last' => 'sometimes|date|nullable',
            'schedule.*.next' => 'sometimes|date|nullable',
            'schedule.*.endDate' => 'sometimes|date|nullable',
        ];
    }
}
