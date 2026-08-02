<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('meeting_address')->nullable()->after('status');
            $table->decimal('meeting_lat', 10, 7)->nullable()->after('meeting_address');
            $table->decimal('meeting_lng', 10, 7)->nullable()->after('meeting_lat');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['meeting_address', 'meeting_lat', 'meeting_lng']);
        });
    }
};