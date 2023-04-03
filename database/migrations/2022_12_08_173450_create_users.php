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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table
              ->string('name', 64)
              ->nullable(false)
              ->comment('氏名');
            $table
              ->string('email', 64)
              ->nullable(false)
              ->comment('メールアドレス');
            $table
              ->string('password', 128)
              ->nullable(false)
              ->comment('パスワード');
            $table
              ->string('verification_code', 255)
              ->nullable(false)
              ->comment('承認コード');
            $table
              ->dateTime('verified_at')
              ->nullable(true)
              ->comment('承認日時');
            $table
              ->enum('status', \App\Enums\User\Status::values())
              ->nullable(false)
              ->default(\App\Enums\User\Status::Pending->value)
              ->comment('ステータス');
            $table
              ->bigInteger('update_user_id')
              ->unsigned()
              ->nullable(true)
              ->comment('更新ユーザーID');
            $table->timestamps();

            $table->softDeletes('deleted_at', 0);

            $table->unique('email');

            $table->comment('ユーザーマスタ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
