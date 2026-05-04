<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ParkingRate;

class RateController extends Controller
{
    public function index()
    {
        $rates = ParkingRate::all();
        return view('admin.rates', compact('rates'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hourly_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $rate = ParkingRate::findOrFail($id);
        $rate->update([
            'hourly_rate' => $request->hourly_rate,
            'is_active' => $request->has('is_active') ? $request->is_active : $rate->is_active,
        ]);

        return redirect()->back()->with('success', 'Rate updated successfully!');
    }
}
