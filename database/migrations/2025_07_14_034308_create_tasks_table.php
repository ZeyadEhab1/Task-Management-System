<?php

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('status')->default(TaskStatusEnum::Pending->value);

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');

            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
