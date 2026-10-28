<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    // Página de inicio: productos de la base separados en Mangas y Cómics.
    public function inicio()
    {
        // vendidos = suma de las cantidades en detalle_compras (0 si todavía no se vendió).
        $productos = Producto::with('serie')
            ->withSum('detalleCompras as vendidos', 'cantidad')
            ->orderBy('nombre')
            ->get();
        $conStock = $productos->where('stock', '>', 0);

        return view('inicio', [
            'mangas' => Producto::with('serie')
                ->where('tipo', Producto::TIPO_MANGA)
                ->orderByRaw('orden IS NULL')
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get(),
            'comics' => $productos->where('tipo', Producto::TIPO_COMIC),
            // Selección editorial de tomos iniciales icónicos, sin afirmar récords de ventas.
            'destacados' => collect([
                'Hunter x Hunter Vol. 1',
                "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1",
                'Shingeki no Kyojin Vol. 1',
            ])->map(fn ($nombre) => $conStock->whereNotNull('imagen')->firstWhere('nombre', $nombre))->filter()->values(),
            // Novedades: los 5 cargados más recientemente.
            'novedades' => $productos->sortBy([['created_at', 'desc'], ['id', 'desc']])->take(5)->values(),
            // Más vendidos: por unidades vendidas; si hay empate (o todavía no hay ventas),
            // primero los mangas y después por nombre.
            'ranking' => $productos->sortBy([
                ['vendidos', 'desc'],
                fn ($a, $b) => ($a->tipo === Producto::TIPO_MANGA ? 0 : 1) <=> ($b->tipo === Producto::TIPO_MANGA ? 0 : 1),
                ['nombre', 'asc'],
            ])->take(5)->values(),
            // Portadas para el collage del fondo.
            'portadas' => $productos->pluck('imagen')->filter()->values(),
        ]);
    }

    // Detalle de un producto. Si el id no existe, Laravel responde 404 solo.
    public function detalle(Producto $producto)
    {
        $producto->load('serie');

        return view('productos.detalle', ['producto' => $producto]);
    }

    // Buscador por GET: /buscar?q=naruto
    public function buscar(Request $request)
    {
        // Si "q" no es texto (ej. ?q[]=x) se ignora; si es muy largo, se recorta a 100 caracteres.
        $q = $request->query('q');
        $texto = is_string($q) ? mb_substr(trim($q), 0, 100) : '';

        $resultados = collect();
        if ($texto !== '') {
            // Busca en el nombre del producto y en el nombre de su serie.
            // Los valores van como parámetros (?), así no hay inyección SQL.
            $resultados = Producto::with('serie')
                ->where('nombre', 'like', "%{$texto}%")
                ->orWhereHas('serie', fn ($consulta) => $consulta->where('nombre', 'like', "%{$texto}%"))
                ->orderBy('nombre')
                ->get();
        }

        return view('productos.buscar', ['texto' => $texto, 'resultados' => $resultados]);
    }
}
