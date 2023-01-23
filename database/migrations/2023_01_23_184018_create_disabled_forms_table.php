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
        Schema::create('disabled_forms', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->date('form_date');
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->integer('form_amount');
            $table->integer('total_form_amounts')->default(0);
            $table->integer('amount');
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
        Schema::dropIfExists('disabled_forms');
    }
};
