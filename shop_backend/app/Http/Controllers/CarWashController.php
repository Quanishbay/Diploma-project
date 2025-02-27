<?php

namespace App\Http\Controllers;

use App\Models\CarWash;
use Illuminate\Http\Request;

class CarWashController extends Controller
{
    public function index(Request $request)
    {
        $getID = $request->query('id');
        $getName = $request->query('name');

        if ($getID) {
            $carWash = CarWash::find($getID);
            if (!$carWash) {
                return response()->json('Car wash not found');
            }
            return $carWash;
        }


        if ($getName) {
            $carWashNames = CarWash::where('name', 'LIKE', '%' . $getName . '%')->get();
            if (!$carWashNames) {
                return response()->json('Car wash not found');
            }
            return $carWashNames;
        }



        return CarWash::all();
    }
}

