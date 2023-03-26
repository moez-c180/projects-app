<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('death_forms', function (Blueprint $table) {
            $table->renameColumn('final_amount', 'original_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('death_forms', function (Blueprint $table) {
            $table->renameColumn('original_amount', 'final_amount');
        });
    }
};
