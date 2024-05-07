<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::latest()->get();
        return view('color.index', compact('colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        Color::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Color created successfully!');
        return back();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('color.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $color->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Color updated successfully!');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();
        toastr()->warning('Color removed successfully!');
        return back();
    }
}
