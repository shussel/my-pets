<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Enums\SpeciesEnum;
use App\Enums\SexEnum;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\PetRequest;
use \Illuminate\Http\RedirectResponse;

class PetController extends Controller
{
    protected function petsResponse(): Response
    {
        return Inertia::render('Pets/Pets', [
            'data' => auth()->user()->pets,
            'meta' => fn() => [
                'species' => SpeciesEnum::options(),
                'sexes' => SexEnum::options(),
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
