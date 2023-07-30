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
        Schema::create('relative_death_forms', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->date('form_date');
            $table->date('death_date');
            $table->string('dead_name');
            $table->string('relative_type');
            $table->foreignId('relative_death_degree_car_rent_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->integer('car_rent')->default(0);
            $table->integer('sub_amount');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relative_death_forms');
    }
};
