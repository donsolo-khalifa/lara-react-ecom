<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 2000);
            $table->string('slug', 2000);
            $table->LongText('description');
            $table->foreignId('department_id')
                ->index()
                ->constrained('departments');
            $table->foreignId('category_id')
                ->index()
                ->constrained('categories');
            $table->decimal('price', 20, 4);
            $table->string('status')->index();
            $table->integer('quantity')->nullable();
            $table->foreignIdFor(User::class,'created_by');
            $table->foreignIdFor(User::class, 'updated_by');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};