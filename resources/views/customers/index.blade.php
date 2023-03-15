@extends('layouts.app')

@section('title')
Customer List
@endsection
@section('content')
    <h1>Customer List</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Parking Bookings</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        @if($customer->bookings->count() > 0)
                            <ul>
                                @foreach($customer->bookings as $booking)
                                    <li>{{ $booking->appointment_number }} - Slot {{ $booking->slot->name }} - Rs. {{ $booking->fee }}</li>
                                @endforeach
                            </ul>
                        @else
                            No bookings found.
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection