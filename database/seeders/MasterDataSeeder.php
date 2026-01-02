<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\RoomType;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Room Types
        $roomTypes = [
            ['name' => 'Standard', 'description' => 'Kamar standar dengan fasilitas dasar'],
            ['name' => 'Deluxe', 'description' => 'Kamar lebih luas dengan fasilitas lengkap'],
            ['name' => 'Suite', 'description' => 'Kamar paling luas dengan fasilitas premium'],
        ];

        foreach ($roomTypes as $type) {
            RoomType::create($type);
        }

        // Room Facilities
        $roomFacilities = [
            ['name' => 'AC', 'type' => 'room', 'icon' => 'air-conditioning', 'description' => 'Air Conditioner'],
            ['name' => 'Kasur', 'type' => 'room', 'icon' => 'bed', 'description' => 'Kasur dengan spring bed'],
            ['name' => 'Lemari', 'type' => 'room', 'icon' => 'wardrobe', 'description' => 'Lemari pakaian'],
            ['name' => 'Meja Belajar', 'type' => 'room', 'icon' => 'desk', 'description' => 'Meja belajar dengan kursi'],
            ['name' => 'Kamar Mandi Dalam', 'type' => 'room', 'icon' => 'bathroom', 'description' => 'Kamar mandi dalam'],
            ['name' => 'TV', 'type' => 'room', 'icon' => 'tv', 'description' => 'Televisi'],
            ['name' => 'Kulkas Mini', 'type' => 'room', 'icon' => 'fridge', 'description' => 'Kulkas kecil'],
        ];

        // Shared Facilities
        $sharedFacilities = [
            ['name' => 'WiFi', 'type' => 'shared', 'icon' => 'wifi', 'description' => 'Internet WiFi'],
            ['name' => 'Parkir Motor', 'type' => 'shared', 'icon' => 'motorcycle', 'description' => 'Area parkir motor'],
            ['name' => 'Parkir Mobil', 'type' => 'shared', 'icon' => 'car', 'description' => 'Area parkir mobil'],
            ['name' => 'Dapur Bersama', 'type' => 'shared', 'icon' => 'kitchen', 'description' => 'Dapur untuk memasak'],
            ['name' => 'Ruang Tamu', 'type' => 'shared', 'icon' => 'living-room', 'description' => 'Ruang tamu bersama'],
            ['name' => 'Laundry', 'type' => 'shared', 'icon' => 'laundry', 'description' => 'Mesin cuci bersama'],
            ['name' => 'CCTV', 'type' => 'shared', 'icon' => 'cctv', 'description' => 'Keamanan CCTV 24 jam'],
            ['name' => 'Keamanan 24 Jam', 'type' => 'shared', 'icon' => 'security', 'description' => 'Satpam 24 jam'],
        ];

        foreach ([...$roomFacilities, ...$sharedFacilities] as $facility) {
            Facility::create($facility);
        }

        // Default Settings
        $settings = [
            // Profile
            ['key' => 'kost_name', 'value' => 'Kost Sejahtera', 'group' => 'profile'],
            ['key' => 'kost_address', 'value' => 'Jl. Contoh No. 123, Kota Contoh', 'group' => 'profile'],
            ['key' => 'kost_phone', 'value' => '081234567890', 'group' => 'profile'],
            ['key' => 'kost_email', 'value' => 'info@kostsejahtera.com', 'group' => 'profile'],
            ['key' => 'kost_description', 'value' => 'Kost nyaman dan aman dengan fasilitas lengkap', 'group' => 'profile'],
            
            // Payment
            ['key' => 'bank_name', 'value' => 'Bank BCA', 'group' => 'payment'],
            ['key' => 'bank_account_name', 'value' => 'PT Kost Sejahtera', 'group' => 'payment'],
            ['key' => 'bank_account_number', 'value' => '1234567890', 'group' => 'payment'],
            ['key' => 'payment_instructions', 'value' => 'Silakan transfer ke rekening di atas dan konfirmasi pembayaran.', 'group' => 'payment'],
            
            // Invoice
            ['key' => 'invoice_prefix', 'value' => 'INV', 'group' => 'invoice'],
            ['key' => 'invoice_footer', 'value' => 'Terima kasih atas kepercayaan Anda.', 'group' => 'invoice'],
            ['key' => 'invoice_due_days', 'value' => 10, 'group' => 'invoice'],
            
            // Rules
            ['key' => 'kost_rules', 'value' => [
                'Dilarang membawa hewan peliharaan',
                'Jam malam pukul 22.00',
                'Tamu menginap wajib lapor',
                'Menjaga kebersihan dan ketenangan',
                'Pembayaran sewa dilakukan setiap tanggal 1-10',
            ], 'group' => 'rules'],
        ];

        foreach ($settings as $setting) {
            Setting::create([
                'key' => $setting['key'],
                'value' => is_array($setting['value']) ? $setting['value'] : $setting['value'],
                'group' => $setting['group'],
            ]);
        }
    }
}
