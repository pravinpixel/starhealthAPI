<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name', 50)->unique();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
        });

        // Insert initial data
        DB::table('states')->insert([
            ['id' => 1, 'code' => 'AP', 'name' => 'Andhra Pradesh', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 2, 'code' => 'JH', 'name' => 'Jharkhand', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 3, 'code' => 'KA', 'name' => 'Karnataka', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 4, 'code' => 'KL', 'name' => 'Kerala', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 5, 'code' => 'MP', 'name' => 'Madhya Pradesh', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 6, 'code' => 'MH', 'name' => 'Maharashtra', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 7, 'code' => 'MN', 'name' => 'Manipur', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 8, 'code' => 'ML', 'name' => 'Meghalaya', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 9, 'code' => 'MZ', 'name' => 'Mizoram', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 10, 'code' => 'NL', 'name' => 'Nagaland', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 11, 'code' => 'OD', 'name' => 'Odisha', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 12, 'code' => 'AR', 'name' => 'Arunachal Pradesh', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 13, 'code' => 'PB', 'name' => 'Punjab', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 14, 'code' => 'RJ', 'name' => 'Rajasthan', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 15, 'code' => 'SK', 'name' => 'Sikkim', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 16, 'code' => 'TN', 'name' => 'Tamil Nadu', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 17, 'code' => 'TS', 'name' => 'Telangana', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 18, 'code' => 'TR', 'name' => 'Tripura', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 19, 'code' => 'UP', 'name' => 'Uttar Pradesh', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 20, 'code' => 'UK', 'name' => 'Uttarakhand', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 21, 'code' => 'WB', 'name' => 'West Bengal', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 22, 'code' => 'AS', 'name' => 'Assam', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 23, 'code' => 'BR', 'name' => 'Bihar', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 24, 'code' => 'CG', 'name' => 'Chhattisgarh', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 25, 'code' => 'GA', 'name' => 'Goa', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 26, 'code' => 'GJ', 'name' => 'Gujarat', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 27, 'code' => 'HR', 'name' => 'Haryana', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 28, 'code' => 'HP', 'name' => 'Himachal Pradesh', 'status' => 1, 'created_at' => '2023-10-27 05:30:29', 'updated_at' => '2023-10-27 05:30:29', 'deleted_at' => null],
            ['id' => 29, 'code' => 'IN', 'name' => 'Berat', 'status' => 1, 'created_at' => '2024-01-16 06:24:15', 'updated_at' => '2024-01-16 06:24:15', 'deleted_at' => null],
            ['id' => 30, 'code' => 'DEL', 'name' => 'Delhi', 'status' => 1, 'created_at' => '2024-02-19 05:14:00', 'updated_at' => '2024-02-19 05:14:00', 'deleted_at' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
