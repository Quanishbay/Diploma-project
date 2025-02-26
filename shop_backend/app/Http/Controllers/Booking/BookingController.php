<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user', 'service')->get();
        return $bookings;
    }


    public function store(BookingRequest $request)
    {
        $validated = $request->validated();
        Booking::create($validated);
        return $validated;
    }

    public function show(Booking $booking)
    {
        return $booking->load('user', 'service');
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending, confirmed, completed, cancelled'
        ]);
        $booking->update($validated);
        return $validated;
    }

    public function destroy(Booking $booking){
        $booking->delete();
        return response()->json(null, 204);
    }
}
