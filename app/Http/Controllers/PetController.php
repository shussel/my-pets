<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Enums\SpeciesEnum;
use App\Enums\SexEnum;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
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
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
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
     * Display the specified resource.
     */
    public function show($pet): \Inertia\Response
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
     * Show the form for editing the specified resource.
     */
    public function edit($pet_id): \Inertia\Response
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'weight' => 'integer|nullable',
            'birth_date' => 'required|date',
            'image' => 'string|nullable',
        ]);

        Auth::user()->pets()->create([
            'name' => $request->name,
            'species' => $request->species,
            'sex' => $request->sex,
            'weight' => $request->weight,
            'birth_date' => $request->birth_date,
            'image' => $request->image,
        ]);

        return to_route('pets.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $pet_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'weight' => 'integer|nullable',
            'birth_date' => 'required|date',
            'image' => 'string|nullable',
        ]);

        auth()->user()->pets()->find($pet_id)->update([
            'name' => $request->name,
            'species' => $request->species,
            'sex' => $request->sex,
            'weight' => $request->weight,
            'birth_date' => $request->birth_date,
            'image' => $request->image,
        ]);

        return redirect()->route('pets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($pet_id)
    {
        auth()->user()->pets()->find($pet_id)->delete();

        return redirect()->route('pets.index');
    }
}
