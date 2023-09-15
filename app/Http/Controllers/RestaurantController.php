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

    public function advancedSearch(String $JsonParams)
    {
        $params = json_decode($JsonParams);
        if($params){
            $searchbar = $params->searchbar;
            $typeIds = $params->type_ids;
            $categoryId = $params->category_id;
    
            $query = User::query();
            if(count($typeIds) > 0){
                foreach ($typeIds as $typeId) {
                    $query->whereHas('types', function ($type) use ($typeId) {
                        $type->where('id', $typeId);
                    });
                }
            }
            if($searchbar != ''){
                $query->where('name', 'like', $searchbar.'%');
            }
            if(gettype($categoryId) != 'string'){
                $query->whereHas('dishes', function ($dishQuery) use ($categoryId) {
                    $dishQuery->where('category_id', '=', $categoryId);
                });
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
}
