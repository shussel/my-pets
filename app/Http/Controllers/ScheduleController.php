<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use Illuminate\Http\RedirectResponse;

class ScheduleController extends Controller
{
    public function update(ScheduleRequest $request, $pet_id): RedirectResponse
    {
        $pet = auth()->user()->pets()->find($pet_id);

        if ($save_schedule = $request->validated()['schedule']) {
            array_walk($save_schedule, function (&$value, $key) {
                $value['updated'] = true;
            });
            if ($category = $request->validated()['category']) {
                $pet->schedule = array_values(array_merge(array_filter($pet->schedule ?: [],
                    function ($event) use ($category) {
                        return $event['category'] !== $category;
                    }), $save_schedule));
            } else {
                $pet->schedule = array_values(array_merge($pet->schedule ?: [], $save_schedule));
            }
            $pet->save();
        }

        return redirect()->route('pets.settings', $pet_id);
    }

    /**
     * Remove schedule item
     */
    public function destroy($pet_id, $item_id): RedirectResponse
    {
        if ($pet = auth()->user()->pets()->find($pet_id)) {
            $pet->schedule = array_values(array_filter($pet->schedule ?: [], function ($item) use ($item_id) {
                return $item['_id'] !== $item_id;
            }));
            $pet->save();
            return redirect()->route('pets.schedule', $pet_id);
        } else {
            return redirect()->route('pets.schedule', $pet_id)->with('error', "Pet not found");
        }
    }
}
