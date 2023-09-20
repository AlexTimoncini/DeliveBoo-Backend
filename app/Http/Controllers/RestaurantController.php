<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dish;
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

    public function advancedSearch(String $JsonParams)
    {
        $params = json_decode($JsonParams);
        if ($params) {
            $searchbar = $params->searchbar;
            $typeIds = $params->type_ids;
            $categoryIds = $params->category_ids;

            $query = User::query();
            if (count($typeIds) > 0) {
                foreach ($typeIds as $typeId) {
                    $query->whereHas('types', function ($type) use ($typeId) {
                        $type->where('id', $typeId);
                    });
                }
            }
            if ($searchbar != '') {
                $query->where('name', 'like', $searchbar . '%');
            }
            if (count($categoryIds) > 0) {
                $query->whereHas('dishes', function ($dishQuery) use ($categoryIds) {
                    $dishQuery->whereIn('category_id', $categoryIds);
                }, '>=', count($categoryIds));
            }
            $restaurants = $query->get();
            return response()->json([
                "success" => true,
                "data" => $restaurants
            ]);
        } else {
            return response()->json([
                "success" => false,
                "error" => 'Wrong Query passed'
            ]);
        }
    }

    public function show(Int $id)
    {
        $restaurant = User::with('types', 'dishes.ingredients',)->findOrFail($id);
        return response()->json([
            "success" => true,
            "data" => $restaurant
        ]);
    }
    
    public function indexDishes(Int $id)
    {
        $dishes = Dish::with('categories', 'ingredients')->where('user_id', $id)->get();
        return response()->json([
            "success" => true,
            "data" => $dishes
        ]);
    }
    
}
