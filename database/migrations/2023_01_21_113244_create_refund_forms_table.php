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
        Schema::create('refund_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_form_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->integer('amount');
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
        Schema::dropIfExists('refund_forms');
    }
};
