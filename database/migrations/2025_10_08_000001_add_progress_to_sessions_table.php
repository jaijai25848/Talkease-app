<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('sessions') && !Schema::hasColumn('sessions','progress')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->decimal('progress', 5, 2)->nullable()->after('user_id'); // 0-100
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions','progress')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropColumn('progress');
            });
        }
    }
};
