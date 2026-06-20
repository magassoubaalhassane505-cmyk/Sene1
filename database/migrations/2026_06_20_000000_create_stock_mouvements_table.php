<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entree', 'utilisation', 'ajustement']);
            $table->string('description')->nullable();
            $table->decimal('quantite', 10, 2);
            $table->decimal('quantite_avant', 10, 2);
            $table->decimal('quantite_apres', 10, 2);
            $table->string('reference')->nullable();
            $table->timestamp('date_mouvement');
            $table->timestamps();
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('stock_minimum', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mouvements');
        
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('stock_minimum');
        });
    }
};