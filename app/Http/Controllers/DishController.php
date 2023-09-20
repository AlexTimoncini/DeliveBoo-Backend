<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataDish = new Dish;
        $dataDish->fill($request->all());
        $dataDish->save();
    
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $dish = Dish::with('ingredients', 'categories')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $dish
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dish $dish)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $dataDish = Dish::findOrFail($id);
        $dataDish->update($request->all());
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, int $id)
    {
        $dataDish = Dish::findOrFail($id);
        $dataDish->delete();
        return response()->json([
            'success' => true,
        ]);
    }
}
