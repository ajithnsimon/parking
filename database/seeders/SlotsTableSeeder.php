<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slot;

class SlotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slots = [];

        $alphabets = range('A', 'Z');
        $priorities = [1, 2, 3, 4, 5];

        foreach ($alphabets as $alphabet) {
            foreach ($priorities as $priority) {
                $slot_name = $alphabet . str_pad($priority, 2, '0', STR_PAD_LEFT);
                $slots[] = ['name' => $slot_name, 'priority' => $priority];
            }
        }

        Slot::insert($slots);
    }
}
