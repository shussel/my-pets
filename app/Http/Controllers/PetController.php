<?php

namespace App\Http\Controllers;

use App\Enums\ChipRegistryEnum;
use App\Enums\CollarColorEnum;
use App\Enums\GpsProviderEnum;
use App\Enums\RepeatsEnum;
use App\Enums\IntervalEnum;
use App\Http\Requests\PetRequest;
use App\Http\Requests\PetSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Enums\SpeciesEnum;
use App\Enums\SexEnum;
use App\Enums\FeedEnum;
use App\Enums\ActivityEnum;
use App\Enums\IntelligenceEnum;
use App\Enums\NoiseEnum;
use App\Enums\SociabilityEnum;

class PetController extends Controller
{
    protected function petsResponse(): Response
    {
        return Inertia::render('Pets/Pets', [
            'data' => auth()->user()->pets,
            'meta' => fn() => [
                'species' => SpeciesEnum::options(),
                'sexes' => SexEnum::options(),
                'schedule' => [
                    'repeats' => RepeatsEnum::options(),
                    'intervals' => IntervalEnum::options(),
                    'presets' => [
                        'feed' => FeedEnum::options(),
                    ]
                ],
                'settings' => [
                    'behavior' => [
                        'activity' => ActivityEnum::options(),
                        'sociability' => SociabilityEnum::options(),
                        'intelligence' => IntelligenceEnum::options(),
                        'noise' => NoiseEnum::options(),
                    ],
                    'identity' => [
                        'collar' => CollarColorEnum::options(),
                        'registry' => ChipRegistryEnum::options(),
                        'gps_provider' => GpsProviderEnum::options(),
                    ]
                ]
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return $this->petsResponse();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->petsResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show($pet): Response
    {
        return $this->petsResponse();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pet_id): Response
    {
        return $this->petsResponse();
    }

    /**
     * Show the form for editing pet settings.
     */
    public function settings($pet_id): Response
    {
        return $this->petsResponse();
    }

    /**
     * Show the form for editing pet settings.
     */
    public function schedule($pet_id): Response
    {
        return $this->petsResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->file('newAvatar')) {
            $validated['avatar'] = $request->file('newAvatar')->store("users/".Auth::user()->_id, 'public');
        }

        Auth::user()->pets()->create($validated);

        return redirect()->route('pets.index')->with('message', "$request->name added");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetRequest $request, $pet_id): RedirectResponse
    {
        $validated = $request->validated();

        // add new avatar
        if ($request->file('newAvatar')) {
            $validated['avatar'] = $request->file('newAvatar')->store("users/".Auth::user()->_id, 'public');
        }

        // delete previous avatar
        if (!$validated['avatar'] && ($previous_avatar = Auth::user()->pets()->find($pet_id)->avatar)) {
            Storage::disk('local')->put(
                'deleted/'.$previous_avatar,
                Storage::disk('public')->get($previous_avatar));
            Storage::disk('public')->delete($previous_avatar);
        }

        auth()->user()->pets()->find($pet_id)->update($validated);

        return redirect()->route('pets.index')->with('message', "$request->name updated");
    }

    public function saveSettings(PetSettingsRequest $request, $pet_id): RedirectResponse
    {
        $save_settings = [];
        foreach ($request->validated() as $category => $settings) {
            if ($category === 'schedule') {
                $save_event = $settings;
            } else {
                $save_settings[$category] = $settings;
            }
        }

        $pet = auth()->user()->pets()->find($pet_id);

        if (count($save_settings)) {
            $pet->settings = array_replace_recursive(is_array($pet->settings) ? $pet->settings : [], $save_settings);
        }
        if ($save_event) {
            $pet->schedule = array_filter($pet->schedule ?: [], function ($event) use ($save_event) {
                return $event['category'] !== $save_event['category'];
            })[] = [$save_event];
        }

        $pet->save();

        return redirect()->route('pets.settings', $pet_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($pet_id): RedirectResponse
    {
        if ($pet = auth()->user()->pets()->find($pet_id)) {
            // move avatar to deleted folder
            if ($pet->avatar) {
                Storage::disk('local')->put(
                    'deleted/'.$pet->avatar,
                    Storage::disk('public')->get($pet->avatar));
                Storage::disk('public')->delete($pet->avatar);
            }
            $pet->delete();
            return redirect()->route('pets.index')->with('message', "$pet->name deleted");
        } else {
            return redirect()->route('pets.index')->with('error', "Pet not found");
        }
    }
}
