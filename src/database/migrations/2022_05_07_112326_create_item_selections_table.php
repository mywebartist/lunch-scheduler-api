<?php

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
        Schema::create('item_selections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
//            $table->unsignedInteger('schedule_id')->nullable();
            $table->unsignedInteger('organization_id');
//            $table->unsignedInteger('item_id')->nullable();
            $table->json('items_ids');
            $table->timestamp('scheduled_at');
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
        Schema::dropIfExists('item_selections');
    }
};
