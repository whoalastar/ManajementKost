<?php

namespace App\Http\Requests\PublicArea;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['nullable', 'exists:rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'planned_check_in' => ['nullable', 'date', 'after_or_equal:today'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.exists' => 'Kamar yang dipilih tidak valid.',
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.max' => 'Nomor HP maksimal 20 karakter.',
            'email.email' => 'Format email tidak valid.',
            'occupation.max' => 'Pekerjaan maksimal 255 karakter.',
            'planned_check_in.date' => 'Format tanggal tidak valid.',
            'planned_check_in.after_or_equal' => 'Tanggal check-in tidak boleh sebelum hari ini.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'room_id' => 'kamar',
            'name' => 'nama',
            'phone' => 'nomor HP',
            'email' => 'email',
            'occupation' => 'pekerjaan',
            'planned_check_in' => 'tanggal check-in',
            'message' => 'pesan',
        ];
    }
}
