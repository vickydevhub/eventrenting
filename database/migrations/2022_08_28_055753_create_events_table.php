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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->char('title');
            $table->string('slug');
            $table->longText('description');
            $table->text('location');
            $table->date('start_date');
            $table->time('start_time');
            $table->char('start_timezone');
            $table->date('end_date');
            $table->time('send_time');
            $table->char('end_timezone');
            $table->integer('min_people_allowed');
            $table->integer('max_people_allowed');
            $table->enum('payment_type',['online','offline'])->default('offline');
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
        Schema::dropIfExists('events');
    }
};
