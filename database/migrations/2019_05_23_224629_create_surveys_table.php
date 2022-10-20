<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->enum('age', ['18-29', '30-44', '45-54', '55+']);
            $table->boolean('has_mobile_phone');
            $table->boolean('helps_decide_provider');
            $table->enum('event_experience', ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent']);
            $table->enum('learned_something', ['Yes', 'No', 'I didn’t see anything about T-Mobile']);
            $table->enum('brand_rating', ['Yes, strongly agree', 'Yes, agree', 'Maybe', 'No, probably not', 'No, definitely not']);
            $table->enum('switch_rating', ['I’m already on T-Mobile!', 'Yes, strongly agree', 'Yes, agree', 'Maybe', 'No, probably not', 'No, definitely not']);
            $table->text('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys');
    }
}
