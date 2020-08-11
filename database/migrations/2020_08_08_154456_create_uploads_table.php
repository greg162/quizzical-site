<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained();
            $table->string('file_name', 255);
            $table->string('file_path', 255);
            $table->string('file_url', 255);
            $table->string('storage_engine', 255);
            $table->string('table_name', 255);
            $table->integer('table_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
