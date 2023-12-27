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
        Review::firstOrcreate([
            'name' => 'شهري'
        ]);
        Review::firstOrcreate([
            'name' => 'سداد نقدي'
        ]);
        Review::firstOrcreate([
            'name' => 'تحت التحصيل'
        ]);
        Review::firstOrcreate([
            'name' => 'نشرة'
        ]);
    }
}
