<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code',6);
            $table->string('mobile',11);
            $table->string('expire_at');
            $table->string('status',1)->comment('0 create 1 use');
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
        Schema::dropIfExists('sms_codes');
    }
}
