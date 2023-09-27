<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dish;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class RestaurantController extends Controller
{
    /**Da modificare qunado avremo implementato le statische per mandare solo i migliori**/
    public function bestRestaurants()
    {
        $restaurants = User::select('users.*')
            ->selectRaw('SUM(orders.total_price) as total_spent')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.successful', 1)
            ->groupBy('users.id')
            ->orderByDesc('total_spent')
            ->limit(4)
            ->get();

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

    public function advancedSearch(string $JsonParams)
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

    public function show(int $id)
    {
        $restaurant = User::with('types', 'dishes.ingredients', )->findOrFail($id);
        return response()->json([
            "success" => true,
            "data" => $restaurant
        ]);
    }

    public function indexDishes(int $id)
    {
        $dishes = Dish::with('categories', 'ingredients')->where('user_id', $id)->get();
        return response()->json([
            "success" => true,
            "data" => $dishes
        ]);
    }

    public function uploadLogo(Request $request, int $id)
    {
        $restaurant = User::findOrFail($id);
        if ($request->hasFile('files')) {
            $nameFile = Storage::put('/logos', $request['files'][0]);
            $restaurant
                ->update(
                    [
                        $restaurant->logo = '/logos/' . pathinfo($nameFile, PATHINFO_FILENAME) . '.' . pathinfo($nameFile, PATHINFO_EXTENSION),
                    ]
                );
            return response()->json([
                'success' => true,
                'status' => true,
            ]);
        } else if (is_null($request)) {
            return response()->json([
                'success' => 'true',
                'status' => true,
                'message' => 'File not modified :D'
            ]);
        }
        return response()->json([
            'messageError' => 'The field is must be a file',
            'status' => false,
        ]);
    }

    public function uploadImage(Request $request, int $id)
    {
        $restaurant = User::findOrFail($id);
        if ($request->hasFile('files')) {
            $nameFile = Storage::put('/restaurants', $request['files'][0]);
            $restaurant
                ->update(
                    [
                        $restaurant->image = '/restaurants/' . pathinfo($nameFile, PATHINFO_FILENAME) . '.' . pathinfo($nameFile, PATHINFO_EXTENSION),
                    ]
                );
            return response()->json([
                'success' => true,
                'status' => true,
            ]);
        } else if (is_null($request)) {
            return response()->json([
                'success' => 'true',
                'status' => true,
                'message' => 'File not modified :D'
            ]);
        }
        return response()->json([
            'messageError' => 'The field is must be a file',
            'status' => false,
        ]);
    }


    public function update(Request $request, int $id)
    {
        $dataRestaurant = User::findOrFail($id);
        $dataRestaurant->update($request->all());
        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteAccount(int $id)
    {
        $restaurant = User::findOrFail($id);
        Storage::delete($restaurant->image);
        Storage::delete($restaurant->logo);
        $restaurant->delete();
        if ($restaurant) {
            return response()->json([
                'success' => 'Account eliminato con successo',
            ]);
        }
        return response()->json([
            'success' => 'Account non eliminato con successo',
        ]);
    }

    //Analytics

    public function bestOrders(int $id)
    {
        $restaurant = User::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Ristorante non trovato',
            ]);
        }

        $bestOrders = Order::where('user_id', $id)
            ->orderByDesc('total_price')
            ->paginate(5)
        ;
        // 


        return response()->json([
            'success' => true,
            'results' => $bestOrders,
        ]);

    }

    public function rankingEver(int $id)
    {
        $restaurant = User::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Ristorante non trovato',
            ]);
        }

        $totalAmount = $restaurant->orders()
            ->where('successful', '1')
            ->sum('total_price');



        if ($totalAmount !== 0) {

            $ranking = User::selectRaw('user_id, SUM(total_price) as total_price')
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->where('successful', '1')
                ->groupBy('user_id')
                ->orderByDesc('total_price')
                ->get()
                ->search(function ($item) use ($id) {
                    return $item->user_id == $id;
                });


            return response()->json([
                'success' => true,
                'data' => [
                    'total_price' => $totalAmount,
                    'ranking' => $ranking + 1,
                    'name' => $restaurant->name,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Il ristorante non ha ordini di successo con importo maggiore di zero.',
            ]);
        }
    }

    public function bestRestaurantEver()
    {
        $winner = User::with('orders')
            ->whereHas('orders', function ($query) {
                $query->where('successful', '1');
            })
            ->get()
            ->sortByDesc(function ($winner) {
                return $winner->orders
                    ->where('successful', '1')
                    ->sum('total_price');
            })
            ->first();
        return response()->json([
            'success' => true,
            'data' => [
                'firstPlace' => $winner
            ],
        ]);
    }

    public function rankingMonth(int $id)
    {
        $restaurant = User::findOrFail($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Ristorante non trovato',
            ]);
        }

        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalAmount = $restaurant->orders()
            ->where('successful', '1')
            ->whereBetween('orders.created_at', [$currentMonth, $endOfMonth])
            ->sum('total_price');

        if ($totalAmount !== 0) {
            $ranking = User::selectRaw('user_id, SUM(total_price) as total_price')
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->where('successful', '1')
                ->whereBetween('orders.created_at', [$currentMonth, $endOfMonth])
                ->groupBy('user_id')
                ->orderByDesc('total_price')
                ->get()
                ->search(function ($item) use ($id) {
                    return $item->user_id == $id;
                });
            return response()->json([
                'success' => true,
                'data' => [
                    'total_price' => $totalAmount,
                    'ranking' => $ranking + 1,
                    'name' => $restaurant->name,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Il ristorante non ha ordini di successo con importo maggiore di zero nel mese corrente.',
            ]);
        }
    }

    public function bestRestaurantMonth()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $winner = User::with('orders')
            ->whereHas('orders', function ($query) use ($currentMonth, $endOfMonth) {
                $query
                    ->where('successful', '1')
                    ->whereBetween('orders.created_at', [$currentMonth, $endOfMonth]);
            })
            ->get()
            ->sortByDesc(function ($winner) {
                return $winner->orders
                    ->where('successful', '1')
                    ->sum('total_price');
            })
            ->first();
        return response()->json([
            'success' => true,
            'data' => [
                'firstPlace' => $winner
            ],
        ]);
    }

    public function bestCustomer(int $id)
    {
        $customer = Order::selectRaw('CONCAT(first_name, " ", last_name) as customer_name')
            ->selectRaw('SUM(total_price) as total_spent')
            ->where('user_id', $id)
            ->where('successful', 1)
            ->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Nessun cliente trovato per il ristorante specificato.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer_name' => $customer->customer_name,
                'total_spent' => $customer->total_spent,
            ],
        ]);
    }
}