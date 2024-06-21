<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert initial data
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'status' => 1, 'created_at' => '2024-06-07 11:50:27', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 2, 'name' => 'User', 'status' => 1, 'created_at' => '2024-06-07 11:50:27', 'updated_at' => null, 'deleted_at' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}

