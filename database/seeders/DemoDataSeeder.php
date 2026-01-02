<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Rooms
        $standardType = RoomType::where('name', 'Standard')->first();
        $deluxeType = RoomType::where('name', 'Deluxe')->first();
        $suiteType = RoomType::where('name', 'Suite')->first();

        $roomFacilities = Facility::where('type', 'room')->pluck('id')->toArray();

        // Create 10 rooms
        $rooms = [
            ['code' => 'A-101', 'name' => 'Kamar A-101', 'floor' => 1, 'room_type_id' => $standardType?->id, 'price' => 800000, 'status' => 'occupied'],
            ['code' => 'A-102', 'name' => 'Kamar A-102', 'floor' => 1, 'room_type_id' => $standardType?->id, 'price' => 800000, 'status' => 'empty'],
            ['code' => 'A-103', 'name' => 'Kamar A-103', 'floor' => 1, 'room_type_id' => $deluxeType?->id, 'price' => 1200000, 'status' => 'occupied'],
            ['code' => 'A-201', 'name' => 'Kamar A-201', 'floor' => 2, 'room_type_id' => $standardType?->id, 'price' => 850000, 'status' => 'empty'],
            ['code' => 'A-202', 'name' => 'Kamar A-202', 'floor' => 2, 'room_type_id' => $deluxeType?->id, 'price' => 1200000, 'status' => 'maintenance'],
            ['code' => 'A-203', 'name' => 'Kamar A-203', 'floor' => 2, 'room_type_id' => $suiteType?->id, 'price' => 1800000, 'status' => 'occupied'],
            ['code' => 'B-101', 'name' => 'Kamar B-101', 'floor' => 1, 'room_type_id' => $standardType?->id, 'price' => 750000, 'status' => 'empty'],
            ['code' => 'B-102', 'name' => 'Kamar B-102', 'floor' => 1, 'room_type_id' => $deluxeType?->id, 'price' => 1100000, 'status' => 'empty'],
            ['code' => 'B-201', 'name' => 'Kamar B-201', 'floor' => 2, 'room_type_id' => $standardType?->id, 'price' => 850000, 'status' => 'empty'],
            ['code' => 'B-202', 'name' => 'Kamar B-202', 'floor' => 2, 'room_type_id' => $suiteType?->id, 'price' => 2000000, 'status' => 'empty'],
        ];

        foreach ($rooms as $roomData) {
            $room = Room::create($roomData);
            // Attach random facilities
            $room->facilities()->attach(array_slice($roomFacilities, 0, rand(3, 5)));
        }

        // Create Tenants with login credentials
        $tenants = [
            [
                'room_id' => Room::where('code', 'A-101')->first()?->id,
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567001',
                'id_card_number' => '3201010101010001',
                'occupation' => 'Karyawan Swasta',
                'check_in_date' => now()->subMonths(6),
                'status' => 'active',
            ],
            [
                'room_id' => Room::where('code', 'A-103')->first()?->id,
                'name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567002',
                'id_card_number' => '3201010101010002',
                'occupation' => 'Mahasiswi',
                'emergency_contact_name' => 'Ibu Rahayu',
                'emergency_contact_phone' => '081234567099',
                'check_in_date' => now()->subMonths(3),
                'status' => 'active',
            ],
            [
                'room_id' => Room::where('code', 'A-203')->first()?->id,
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567003',
                'id_card_number' => '3201010101010003',
                'occupation' => 'Pengusaha',
                'check_in_date' => now()->subMonths(1),
                'status' => 'active',
            ],
        ];

        foreach ($tenants as $tenantData) {
            Tenant::create($tenantData);
        }

        $this->command->info('Demo data created:');
        $this->command->info('- 10 rooms');
        $this->command->info('- 3 tenants with login credentials:');
        $this->command->info('  * budi@example.com / password123');
        $this->command->info('  * siti@example.com / password123');
        $this->command->info('  * ahmad@example.com / password123');
    }
}
