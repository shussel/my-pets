<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use Illuminate\Http\RedirectResponse;

class ScheduleController extends Controller
{
    public function update(ScheduleRequest $request, $pet_id): RedirectResponse
    {
        $pet = auth()->user()->pets()->find($pet_id);

        if ($request->validated()['schedule']) {
            if ($category = $request->validated()['category']) {
                $pet->schedule = array_merge(array_filter($pet->schedule ?: [], function ($event) use ($category) {
                    return $event['category'] !== $category;
                }), $request->validated()['schedule']);
            } else {
                $pet->schedule = array_merge($pet->schedule ?: [], $request->validated()['schedule']);
            }
        }

        $pet->save();

        return redirect()->route('pets.settings', $pet_id);
    }
}
