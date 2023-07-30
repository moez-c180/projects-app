<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AgeForm;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('age_forms', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->enum('age_form_type', array_values(AgeForm::AGE_FORM_VALUES));
            $table->foreignId('member_id')->index()->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->date('form_date');
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
        Schema::dropIfExists('age_forms');
    }
};
