<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone');//PHONE
            $table->string('fullname');//FULLNAME
            $table->string('organization')->nullable();//ORGANIZATION
            $table->string('inn')->nullable();//INN
            $table->string('email')->nullable();//EMAIL
            $table->string('address')->nullable();//ADDRESS
            $table->bigInteger('user_id')->nullable();;//ADDRESS
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
        Schema::dropIfExists('contacts');
    }
}
