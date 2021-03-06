<?php

use App\Models\Plan;
use App\Models\User;
use App\Models\Worker as WorkerAlias;
use App\Models\WorkerUser;
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
        Schema::create('worker_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(WorkerAlias::class, 'worker_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->morphs('plan');
            $table->jsonb('after_images')->nullable();
            $table->jsonb('before_images')->nullable();
            $table->tinyInteger('rate')->nullable()->index();
            $table->tinyInteger('order_status')->unsigned()->default(WorkerUser::ORDER_STATUS['pending'])->index();
            $table->tinyInteger('user_status')->unsigned()->default(WorkerUser::USER_STATUS['pending'])->index();
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
        Schema::dropIfExists('worker_user');
    }
};
