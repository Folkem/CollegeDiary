<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonScheduleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('call_schedule_item_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('week_day_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('variant')->default('чисельник');
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
        Schema::dropIfExists('lesson_schedule_items');
    }
}
