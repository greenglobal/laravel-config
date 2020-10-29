<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGGConfigTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (env('STORE_DB', 'database') == 'database') {
            Schema::create('gg_config', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('value');
                $table->string('default')->nullable();
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
        Schema::dropIfExists('gg_config');
    }
}
