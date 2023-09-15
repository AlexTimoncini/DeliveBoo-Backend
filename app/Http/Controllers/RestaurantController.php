<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**Da modificare qunado avremo implementato le statische per mandare solo i migliori**/
    public function bestRestaurants()
    {
        $restaurants = User::where('id', '<', 9)->get();
        return response()->json([
            "success" => true,
            "data" => $restaurants
        ]);
    }

    public function newInTown()
    {
        $restaurants = User::whereDate('created_at', '<=', date('Y-m-d H:i:s'))
        ->where('created_at', '>', date('Y-m-d H:i:s', strtotime("-3 days")))->get();
        return response()->json([
            "success" => true,
            "data" => $restaurants
        ]);
    }

    public function search(String $name)
    {
        $restaurants = User::where('name', 'like', $name.'%')->get();
        return response()->json([
            "success" => true,
            "data" => $restaurants
        ]);
    }

    public function searchByType(Request $request)
    {
        dd($request);
        $restaurants = User::whereHas('types', fn($type)=>$type->where('id', '=', $id))->get();
        return response()->json([
            "success" => true,
            "data" => $restaurants
        ]);
    }
}
