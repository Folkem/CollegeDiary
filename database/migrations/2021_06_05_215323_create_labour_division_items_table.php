<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabourDivisionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labour_division_items', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->foreignId('group_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('teacher_id')->references('id')->on('users')
                ->constrained()->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('labour_division_items');
    }
}
