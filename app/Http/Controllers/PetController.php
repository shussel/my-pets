<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Enums\SpeciesEnum;
use App\Enums\SexEnum;
use Inertia\Inertia;

class PetController extends Controller
{
    protected function petsResponse(): \Inertia\Response
    {
        return Inertia::render('Pets', [
            'pets' => auth()->user()->pets,
            'meta' => [
                'species' => SpeciesEnum::options(),
                'sexes' => SexEnum::options(),
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        return $this->petsResponse();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return $this->petsResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show($pet): \Inertia\Response
    {
        return $this->petsResponse();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pet_id): \Inertia\Response
    {
        return $this->petsResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'weight' => 'integer|nullable',
            'birth_date' => 'required|date',
        ]);

        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store("users/".Auth::user()->_id, 'public');
        } else {
            $path = $request->avatar;
        }

        Auth::user()->pets()->create([
            'name' => $request->name,
            'species' => $request->species,
            'sex' => $request->sex,
            'weight' => $request->weight,
            'birth_date' => $request->birth_date,
            'avatar' => $path,
        ]);

        return redirect()->route('pets.index')->with('message', "$request->name added");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $pet_id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'weight' => 'integer|nullable',
            'birth_date' => 'required|date',
        ]);

        // add new avatar
        if ($request->file('newAvatar')) {
            $path = $request->file('newAvatar')->store("users/".Auth::user()->_id, 'public');
            if ($request->avatar) {
                // delete current avatar
                Storage::disk('public')->delete($request->avatar);
            }
        } elseif (!($path = $request->avatar) && ($previous_avatar = Auth::user()->pets()->find($pet_id)->avatar)) {
            // delete previous avatar
            Storage::disk('public')->delete($previous_avatar);
        }
        auth()->user()->pets()->find($pet_id)->update([
            'name' => $request->name,
            'species' => $request->species,
            'sex' => $request->sex,
            'weight' => $request->weight,
            'birth_date' => $request->birth_date,
            'avatar' => $path,
        ]);

        return redirect()->route('pets.index')->with('message', "$request->name updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($pet_id): \Illuminate\Http\RedirectResponse
    {
        if ($pet = auth()->user()->pets()->find($pet_id)) {
            if ($pet->avatar) {
                Storage::disk('public')->delete($pet->avatar);
            }
            $pet->delete();
            return redirect()->route('pets.index')->with('message', "$pet->name deleted");
        } else {
            return redirect()->route('pets.index')->with('error', "Pet not found");
        }
    }
}
