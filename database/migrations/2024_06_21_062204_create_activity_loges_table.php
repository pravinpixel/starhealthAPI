<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_loges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user', 255);
            $table->string('action', 255);
            $table->string('information', 100)->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('message', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });

        // If you want to set AUTO_INCREMENT and add primary key constraint, you can do it like this:
        // Schema::table('activity_loges', function (Blueprint $table) {
        //     $table->bigIncrements('id')->unsigned()->change();
        //     $table->primary('id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_loges');
    }
}
