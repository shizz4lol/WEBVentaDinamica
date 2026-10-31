<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
        $mangas = Producto::with('serie')
            ->where('tipo', Producto::TIPO_MANGA)
            ->orderByRaw('orden IS NULL')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();
        $comics = $productos->where('tipo', Producto::TIPO_COMIC);
        $novedades = $productos->sortBy([['created_at', 'desc'], ['id', 'desc']])->take(5)->values();
        $ranking = $productos->sortBy([
            ['vendidos', 'desc'],
            fn ($a, $b) => ($a->tipo === Producto::TIPO_MANGA ? 0 : 1) <=> ($b->tipo === Producto::TIPO_MANGA ? 0 : 1),
            ['nombre', 'asc'],
        ])->take(5)->values();
        $destacados = collect([
            'Hunter x Hunter Vol. 1',
            "JoJo's Bizarre Adventure Parte 1: Phantom Blood Vol. 1",
            'Shingeki no Kyojin Vol. 1',
        ])->map(fn ($nombre) => $conStock->whereNotNull('imagen')->firstWhere('nombre', $nombre))->filter()->values();

        return view('inicio', [
            'mangas' => $mangas,
            'comics' => $comics,
            // Selección editorial de tomos iniciales icónicos, sin afirmar récords de ventas.
            'destacados' => $destacados,
            'novedades' => $novedades,
            'ranking' => $ranking,
            'productosReact' => [
                'novedades' => $this->productosParaReact($novedades),
                'ranking' => $this->productosParaReact($ranking),
                'mangas' => $this->productosParaReact($mangas),
                'comics' => $this->productosParaReact($comics),
            ],
            // Portadas para el collage del fondo.
            'portadas' => $productos->pluck('imagen')->filter()->values(),
        ]);
    }

    // Resuelve el slug canónico y redirige los enlaces numéricos históricos.
    public function detalle(string $producto): View|RedirectResponse
    {
        $productoEncontrado = Producto::query()->where('slug', $producto)->first();

        if ($productoEncontrado === null && ctype_digit($producto)) {
            $productoEncontrado = Producto::findOrFail((int) $producto);

            return redirect()->route('productos.detalle', $productoEncontrado);
        }

        abort_if($productoEncontrado === null, 404);
        $productoEncontrado->load('serie');

        return view('productos.detalle', ['producto' => $productoEncontrado]);
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

        return view('productos.buscar', [
            'texto' => $texto,
            'resultados' => $resultados,
            'productosReact' => $this->productosParaReact($resultados),
        ]);
    }

    /**
     * @param  iterable<Producto>  $productos
     * @return list<array{
     *     id: int,
     *     nombre: string,
     *     tipo: string,
     *     serie: string|null,
     *     numero: int|string|null,
     *     precio: string,
     *     imagen: string|null,
     *     urlDetalle: string,
     *     sinStock: bool,
     *     pocasUnidades: bool,
     *     esNuevo: bool
     * }>
     */
    private function productosParaReact(iterable $productos): array
    {
        $datos = [];
        $fechaLimiteNuevo = now()->subDays(30);

        foreach ($productos as $producto) {
            $datos[] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'tipo' => $producto->tipo,
                'serie' => $producto->serie?->nombre,
                'numero' => $producto->numero,
                'precio' => $producto->precioFormateado(),
                'imagen' => $producto->imagen ? asset($producto->imagen) : null,
                'urlDetalle' => route('productos.detalle', $producto),
                'sinStock' => $producto->stock === 0,
                'pocasUnidades' => $producto->stock > 0 && $producto->stock <= 3,
                'esNuevo' => $producto->created_at?->gte($fechaLimiteNuevo) ?? false,
            ];
        }

        return $datos;
    }
}
