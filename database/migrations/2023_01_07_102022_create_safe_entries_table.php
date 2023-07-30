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
        Schema::create('safe_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_entry_category_id')->index()->constrained()->cascadeOnDelete();
            $table->morphs('payable');
            $table->integer('amount');
            $table->string('contact_name')->nullable();
            $table->text('description');
            $table->dateTime('operation_time');
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
        Schema::dropIfExists('safe_entries');
    }
};
