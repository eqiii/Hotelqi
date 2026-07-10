<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StoreRestaurantCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serve_type'     => ['required', 'in:now,scheduled'],
            'serve_time'     => ['nullable', 'required_if:serve_type,scheduled', 'date'],
            'dining_type'    => ['required', 'in:dine_in,room_service'],

            // guest_name is required when dining_type === dine_in.
            // Using 'sometimes' prevents a submitted-but-empty string from
            // passing the nullable check when the field IS present.
            'guest_name'     => ['sometimes', 'required_if:dining_type,dine_in', 'string', 'max:255'],

            // room_number must exist in the rooms table when present.
            'room_number'    => [
                'nullable',
                'required_if:dining_type,room_service',
                'string',
                'max:50',
                'exists:rooms,room_number',
            ],

            'notes'          => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:midtrans,manual_transfer'],
        ];
    }

    /**
     * After base validation passes, verify that the room belongs to an
     * active booking of the current guest (for room_service orders).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('dining_type') !== 'room_service') {
                return;
            }

            $roomNumber = $this->input('room_number');
            if (empty($roomNumber)) {
                return; // already caught by required_if above
            }

            $user = Auth::user();
            $guest = $user?->guest;

            if (!$guest) {
                $validator->errors()->add('room_number', 'Tamu tidak ditemukan.');
                return;
            }

            $hasActiveBooking = $guest->bookings()
                ->active()
                ->whereHas('room', fn($q) => $q->where('room_number', $roomNumber))
                ->exists();

            if (!$hasActiveBooking) {
                $validator->errors()->add(
                    'room_number',
                    'Nomor kamar tidak ditemukan atau tidak memiliki booking aktif untuk kamar ini.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'room_number.exists'      => 'Nomor kamar tidak ditemukan.',
            'room_number.required_if' => 'Nomor kamar wajib diisi untuk layanan kamar.',
            'guest_name.required_if'  => 'Nama tamu wajib diisi untuk makan di restoran.',
            'serve_time.required_if'  => 'Waktu penyajian wajib diisi jika memilih jadwal.',
        ];
    }
}
