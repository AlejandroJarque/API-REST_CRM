<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('title')->after('client_id');
            $table->string('status')->default('pending')->index()->after('title');
            $table->date('date')->after('status');
            $table->text('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['title', 'status', 'date']);

            $table->text('description')->nullable(false)->change();
        });
    }
};
