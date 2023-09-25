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
        $newOrder = new Order();
        $newOrder->customer_address = $request->address;
        $newOrder->user_id = $request->cart[0]['user_id'];
        $newOrder->first_name = $request->name;
        $newOrder->last_name = $request->surname;
        $newOrder->doorbell = $request->name . ' ' . $request->surname;
        $newOrder->phone = $request->telephone;
        $newOrder->total_price = $request->totalPrice;
        $newOrder->interior = 'A';
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
                'message' => "Transazione eseguita con Successo!"
            ];
            $newOrder->successful = true;
            $newOrder->save();
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => "Transazione Fallita!!"
            ];
            $newOrder->successful = false;
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
        $order = Order::with(['dishes' => function ($query) {
            $query->withTrashed()->withPivot('quantity');;
        }])->findOrFail($id);
        return response()->json([
            "success" => true,
            "data" => $order
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
