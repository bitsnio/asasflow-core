<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // @asasflow:columns:start
            $table->foreignId('tenant_id')->index();
            $table->foreignId('site_id')->nullable()->index();
            $table->string('firstName', 255);
            $table->string('lastName', 255);
            $table->string('email', 255);
            $table->string('password', 255)->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('role_id', 255);
        // @asasflow:columns:end
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['tenant_id', 'email'], 'users_tenant_id_email_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
