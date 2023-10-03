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
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('review_id')
                ->nullable()
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $table->dropColumn('review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('review')->nullable();
            $table->dropForeign(['review_id']);
            $table->dropColumn('review_id');
        });
    }
};
