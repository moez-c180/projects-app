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
        Schema::create('category_rank', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignId('rank_id')->nullable()->index()->constrained()->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_rank');
    }
};
