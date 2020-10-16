<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelConfigTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (env('STORE_CONFIG', 'database') === 'database') {
            Schema::create('laravel_config', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('value');
                $table->string('dèault')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_config');
    }
}
