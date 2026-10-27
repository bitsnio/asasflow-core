<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            // @asasflow:columns:start
            $table->foreignId('tenant_id')->index();
            $table->string('name', 255);
            $table->string('code', 255);
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            // @asasflow:columns:end
            $table->unique(['tenant_id', 'code']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
