<?php

namespace App\Http\Requests;

use App\Enums\SexEnum;
use App\Enums\SpeciesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PetRequest extends FormRequest
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
            '_id' => 'sometimes|string|nullable',
            'name' => 'required|string|max:255',
            'species' => ['required', Rule::enum(SpeciesEnum::class)],
            'sex' => ['required', Rule::enum(SexEnum::class)],
            'weight' => 'integer|nullable|min:0|max:3000',
            'birth_date' => 'required|date|before:today',
            'avatar' => ['nullable', 'string'],
            'newAvatar' => ['nullable', 'file', 'image'],
            'message' => 'nullable|string|max:100',
        ];
    }
}
