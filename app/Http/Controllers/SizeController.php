<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::latest()->get();
        return view('size.index', compact('sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        Size::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Size created successfully!');
        return back();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        return view('size.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $size->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        toastr()->success('Size updated successfully!');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $size->delete();
        toastr()->warning('Size removed successfully!');
        return back();
    }
}
