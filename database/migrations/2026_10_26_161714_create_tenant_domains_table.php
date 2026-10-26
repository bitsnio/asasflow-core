<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_domains', function (Blueprint $table) {
            $table->id();
            // @asasflow:columns:start
            $table->string('domain', 255);
            $table->boolean('is_primary')->nullable()->default(false);
            $table->boolean('is_verified')->nullable()->default(false);
        // @asasflow:columns:end
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_domains');
    }
};
