<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => "required|min:3|max:100",
            'description' => 'min:3',
            'price' => 'required|numeric',
            'photo' => 'required|url:https',
            'available' => 'required|boolean',
            'visible' => 'required|boolean'
        ]);

        $dataDish = new Dish;
        $dataDish->fill($data);
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

    public function upload(Request $request, Int $id)
    {
        $dataDish = Dish::findOrFail($id);
        if ($request->hasFile('files')) {
            $nameFile =  Storage::put('/dish', $request['files'][0]);
            $dataDish->update(
                [
                    $dataDish->photo = '/dish/' . pathinfo($nameFile, PATHINFO_FILENAME) . '.' . pathinfo($nameFile, PATHINFO_EXTENSION),
                ]
            );
            return response()->json([
                'success' => true,
                'status' => true,
            ]);
        }
        return response()->json([
            'messageError' => 'The field is must be a file',
            'status' => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        $dataDish = Dish::findOrFail($id);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => "required|min:3|max:100",
            'description' => 'max:65535',
            'price' => 'required|numeric',
            'available' => 'boolean',
            'visible' => 'boolean'
        ]);
        $dataDish->update($data);
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
