<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serve_type' => ['required', 'in:now,scheduled'],
            'serve_time' => ['nullable', 'required_if:serve_type,scheduled', 'date'],
            'dining_type' => ['required', 'in:dine_in,room_service'],
            'guest_name' => ['nullable', 'required_if:dining_type,dine_in', 'string', 'max:255'],
            'room_number' => ['nullable', 'required_if:dining_type,room_service', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:midtrans,manual_transfer'],
        ];
    }
}
