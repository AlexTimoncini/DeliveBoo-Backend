<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ImagesController extends Controller
{
    public function show(Int $id)
    {
        
        $restaurant = User::FindOrFail($id);
        return response()->json(['logoFileName' => $restaurant->logo]);
    }
}

