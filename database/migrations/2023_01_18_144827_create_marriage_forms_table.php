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
        Schema::create('marriage_forms', function (Blueprint $table) {
            $table->id();
            $table->date('form_date');
            $table->string('serial');
            $table->boolean('is_relative')->default(0);
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->date('marriage_date');
            $table->string('relative_type')->nullable();
            $table->string('relative_name')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('marriage_forms');
    }
};
