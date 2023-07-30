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
        Schema::create('member_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->enum('type', ['deposit', 'withdraw'])->index();
            $table->foreignId('membership_sheet_import_id')->nullable()->index()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('member_wallets');
    }
};
