<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGgFirebaseStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gg_firebase_storage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('url');
            $table->string('destination');
            $table->string('type');
            $table->string('expires')->nullable();
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
        Schema::dropIfExists('gg_firebase_storage');
    }
}
