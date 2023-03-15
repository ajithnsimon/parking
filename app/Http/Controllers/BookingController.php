<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Slot;
use App\Traits\CalculateParkingFee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use App\Traits\CalculatesParkingFee;

class BookingController extends Controller
{
    use CalculatesParkingFee;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $rules = array(
            'name' => 'required',
            'phone'    => 'required',
            'driver_license'    => 'required|mimes:pdf|min:2000|max:5000',
            'vehicle_number'    => 'required',
            'start_time'    => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        );
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json( $validator->errors(), 422);
        }

        $dateTimeS = new DateTime($request['start_time']);
        $request['start_time'] = $dateTimeS->format('Y-m-d H:i:s');

        $dateTimeE = new DateTime($request['end_time']);
        $request['end_time'] = $dateTimeE->format('Y-m-d H:i:s');
        
        $existing = Booking::where('vehicle_number', $request['vehicle_number'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request['start_time'], $request['end_time']])
                    ->orWhereBetween('end_time', [$request['start_time'], $request['end_time']])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<', $request['start_time'])
                            ->where('end_time', '>', $request['end_time']);
                    });
            })
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Vehicle already booked in another time',
            ], 422);
        }

        $available_slots = Slot::whereDoesntHave('bookings', function ($query) use ($request) {
                $query->whereBetween('start_time', [$request['start_time'], $request['end_time']])
                    ->orWhereBetween('end_time', [$request['start_time'], $request['end_time']])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<', $request['start_time'])
                            ->where('end_time', '>', $request['end_time']);
                    });
            })
            ->orderBy('priority', 'asc')
            ->get();

        if ($available_slots->isEmpty()) {
            return response()->json([
                'message' => 'Slots not available at given time',
            ], 422);
        }

        $slot = $available_slots->first();

        $customer = Customer::where('phone', $request['phone'])
            ->first();

        if ($customer) {
            $customer_id = $customer->id;
        } else {
            $new_customer = new Customer();
            $new_customer->name = $request['name'];
            $new_customer->phone = $request['phone'];
            $new_customer->save();
            $customer_id = $new_customer->id;
        }
        
        $booking = new Booking;
        $booking->appointment_number = $slot->name . '-' . $slot->getNextSlotNumber($slot->id);
        $booking->vehicle_number = $request['vehicle_number'];
        $booking->start_time = $request['start_time'];
        $booking->end_time = $request['end_time'];
        $booking->driver_license = $request->file('driver_license')->store('driver_licenses');
        $booking->fee = $this->calculateFee($request['start_time'], $request['end_time']);
        $booking->customer_id = $customer_id;
        $booking->slot_id = $slot->id;
        $booking->save();

        $response = [
            'appointment_number' => $booking->appointment_number,
            'slot_number' => $slot->name,
            'parking_fee' => $booking->fee,
        ];
        
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
