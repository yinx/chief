<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 32)->index();
            $table->timestamps();
        });

        Schema::create('form_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id')->unsigned();
            $table->json('fields');
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });

        // Schema::create('form_labels', function(Blueprint $table){
        //     $table->increments('id');
        //     $table->integer('form_id')->unsigned();
        //     $table->timestamps();

        //     $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        // });

        // Schema::create('form_label_translations', function(Blueprint $table){
        //     $table->increments('id');
        //     $table->integer('form_label_id')->unsigned();
        //     $table->string('locale');
        //     $table->string('label')->nullable();
        //     $table->timestamps();

        //     $table->foreign('form_label_id')->references('id')->on('form_labels')->onDelete('cascade');
        // });

        // Schema::create('form_validations', function(Blueprint $table){
        //     $table->increments('id');
        //     $table->integer('form_label_id')->unsigned();
        //     $table->boolean('optional')->default(false);
        //     $table->timestamps();

        //     $table->foreign('form_label_id')->references('id')->on('form_labels')->onDelete('cascade');
        // });

        // Schema::create('form_validation_translations', function(Blueprint $table){
        //     $table->increments('id');
        //     $table->integer('form_validation_id')->unsigned();
        //     $table->string('locale');
        //     $table->string('message');
        //     $table->timestamps();

        //     $table->foreign('form_validation_id')->references('id')->on('form_labels')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_validation_translations');
        Schema::dropIfExists('form_validations');
        Schema::dropIfExists('form_label_translations');
        Schema::dropIfExists('form_labels');
        Schema::dropIfExists('form_entries');
        Schema::dropIfExists('forms');
    }
}
