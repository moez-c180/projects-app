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
        Schema::create('death_forms', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->date('form_date');
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->date('death_date');
            $table->boolean('human_tribute_car')->default(0);
            $table->boolean('pall')->default(0);
            
            $table->integer('total_form_amounts')->default(0);
            $table->integer('funeral_fees')->default(0);
            $table->integer('late_payments_amount')->default(0);
            $table->integer('amount');
            $table->integer('final_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('death_forms');
    }
};
