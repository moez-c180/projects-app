<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Review::create([
            'name' => 'شهري'
        ]);
        Review::create([
            'name' => 'سداد نقدي'
        ]);
        Review::create([
            'name' => 'تحت التحصيل'
        ]);
        Review::create([
            'name' => 'نشرة'
        ]);
    }
}
