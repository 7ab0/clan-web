<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro manual de resultados (posts/stories/reels/videos) que cada
     * influencer publicó sobre el pre-cóctel — no hay integración con
     * Instagram/TikTok, todo se carga a mano desde el panel admin.
     */
    public function up(): void
    {
        Schema::create('influencer_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('influencer_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['post', 'story', 'reel', 'video']);
            $table->string('url')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->date('published_at');
            $table->unsignedInteger('views')->nullable();
            $table->unsignedInteger('likes')->nullable();
            $table->unsignedInteger('shares')->nullable();
            $table->unsignedInteger('comments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('influencer_posts');
    }
};
