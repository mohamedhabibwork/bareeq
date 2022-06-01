<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('single_requests', function (Blueprint $table) {
            $table->id();
            $table->string('car_name');
            $table->string('car_type');
            $table->string('phone');
            $table->string('address');
            $table->string('car_area');
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnUpdate();
            $table->softDeletes();
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
        Schema::dropIfExists('single_requests');
    }
};
