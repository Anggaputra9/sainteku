<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trx_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20);
            $table->unsignedBigInteger('achievement_type_id');
            $table->unsignedBigInteger('achievement_level_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('achievement_date');
            $table->string('publication_type', 50)->nullable();
            $table->string('publisher')->nullable();
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->string('approved_by', 20)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('unit_id', 4)->nullable();
            $table->timestamps();

            // Index aja dulu, foreign key nya di-skip
            $table->index('user_id');
            $table->index('status');
            $table->index('achievement_date');
            $table->index('achievement_type_id');
            $table->index('achievement_level_id');

            // COMMENT DULU FOREIGN KEY NYA
            // $table->foreign('user_id')->references('id')->on('mst_user')->onDelete('cascade');
            // $table->foreign('achievement_type_id')->references('id')->on('mst_achievement_type');
            // $table->foreign('achievement_level_id')->references('id')->on('mst_achievement_level');
            // $table->foreign('approved_by')->references('id')->on('mst_user')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_achievements');
    }
};
