<?php

namespace App\Http\Controllers;

use Braintree\Gateway;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function generate(Request $request, Gateway $gateway)
    {
        $token = $gateway->clientToken()->generate();
        $data = [
            'success' => true,
            'token' => $token
        ];

        return response()->json($data, 200);
    }
    public function makePayment(Request $request, Gateway $gateway)
    {

        $data =  $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'surname' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:3'],
            'address' => ['required', 'string', 'max:255', 'min:3'],
            'telephone' => ['required'],
            'interior' => ['nullable', 'digits_between:1,3'],
            'doorbell' => ['nullable', 'digits_between:1,3']
        ]);

        $newOrder = new Order();
        $newOrder->customer_address = $data['address'];
        $newOrder->user_id = $request->cart[0]['user_id'];
        $newOrder->first_name = $data['name'];
        $newOrder->last_name = $data['surname'];
        $newOrder->doorbell = $data['name'] . ' ' . $data['surname'];
        $newOrder->phone = $data['telephone'];
        $newOrder->total_price = $request->totalPrice;
        $newOrder->interior = $data['interior'];
        $newOrder->doorbell = $data['doorbell'];
        $result = $gateway->transaction()->sale([
            'amount' => $request->totalPrice,
            'paymentMethodNonce' => $request->nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success) {
            $data = [
                'success' => true,
                'message' => "Transazione eseguita con Successo!",
                'order' => $newOrder
            ];
            $newOrder->successful = true;
            $newOrder->save();
            foreach ($request->cart as $dish) {
                $newOrder->dishes()->attach($dish['id'], ['quantity' => $dish['quantity']]);
            }
            $newOrder->save();
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => "Transazione Fallita!!"
            ];
            $newOrder->successful = false;
            $newOrder->save();
            foreach ($request->cart as $dish) {
                $newOrder->dishes()->attach($dish['id'], ['quantity' => $dish['quantity']]);
            }
            $newOrder->save();
            return response()->json($data, 401);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
        $order = Order::with([
            'dishes' => function ($query) {
                $query->withTrashed()->withPivot('quantity');;
            }
        ])->findOrFail($id);
        return response()->json([
            "success" => true,
            "data" => $order
        ]);
    }

    public function index(Int $id)
    {
        $orders = Order::where('user_id', $id)->paginate(8);
        $ordersAll = Order::where('user_id', $id)->get();
        return response()
            ->json([
                'success' => true,
                'results' => $orders,
                'array_length' => count($ordersAll)
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
