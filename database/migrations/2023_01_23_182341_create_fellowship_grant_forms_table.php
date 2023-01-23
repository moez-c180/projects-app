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
        Schema::create('fellowship_grant_forms', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->date('form_date');
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->integer('grant_amount');
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
        Schema::dropIfExists('fellowship_grant_forms');
    }
};
