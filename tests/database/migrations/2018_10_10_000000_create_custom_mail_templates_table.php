<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomMailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('custom_mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mailable');
            $table->text('subject')->nullable();
            $table->text('template');
            $table->boolean('use')->default(false);
            $table->timestamps();
        });
    }
}
