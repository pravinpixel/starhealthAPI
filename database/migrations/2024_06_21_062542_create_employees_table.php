<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 20)->nullable();
            $table->string('employee_name', 100)->nullable();
            $table->string('department', 50)->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->date('dob')->nullable();
            $table->string('otp', 50)->nullable();
            $table->tinyInteger('otp_verified')->default(0);
            $table->string('family_photo', 255)->nullable();
            $table->enum('status', ['basic', 'upload', 'summary', 'completed'])->default('basic');
            $table->dateTime('expired_date')->nullable();
            $table->string('passport_photo', 255)->nullable();
            $table->enum('employee_status', ['pending', 'register', 'shortlist', 'final'])->nullable();
            $table->string('profile_photo', 255)->nullable();
            $table->string('state', 100)->nullable();
            $table->text('token')->nullable();
            $table->text('session_token')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}


