<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('id')->on('users')
                ->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('lesson_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('grade')->nullable();
            $table->boolean('is_present');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
