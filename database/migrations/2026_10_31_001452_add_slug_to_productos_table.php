<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nombre');
        });

        $slugsUtilizados = [];

        DB::table('productos')
            ->select(['id', 'nombre'])
            ->orderBy('id')
            ->each(function (object $producto) use (&$slugsUtilizados): void {
                $base = Str::slug($producto->nombre) ?: 'producto';
                $base = ctype_digit($base) ? 'producto-'.$base : $base;
                $slug = $base;
                $sufijo = 2;

                while (isset($slugsUtilizados[$slug])) {
                    $slug = $base.'-'.$sufijo;
                    $sufijo++;
                }

                $slugsUtilizados[$slug] = true;

                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('productos', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique('productos_slug_unique');
            $table->dropColumn('slug');
        });
    }
};
