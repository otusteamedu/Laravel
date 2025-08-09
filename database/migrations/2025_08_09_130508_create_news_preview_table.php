<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\News;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_previews', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(News::class)->unique();
            $table->string('text')->nullable();
            $table->string('photo_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            Schema::dropIfExists('news_previews');
        });
    }
};
