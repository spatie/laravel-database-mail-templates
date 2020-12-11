<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('mailable');
            $table->text('subject')->nullable();
            $table->text('html_template');
            $table->text('text_template')->nullable();
            $table->string('code')->nullable();
            $table->string('label')->nullable();

            $table->unsignedInteger('type_id');
            $table->foreign('type_id')
                ->references('id')
                ->on('mail_template_types')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }
}
