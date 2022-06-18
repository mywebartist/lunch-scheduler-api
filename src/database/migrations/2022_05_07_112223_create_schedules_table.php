<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('schedules', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedInteger('organization_id');
//            $table->json('items_ids');
//            $table->timestamp('scheduled_at');
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
