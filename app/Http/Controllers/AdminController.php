<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Serie;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class AdminController extends Controller
{
    public function panel(): View
    {
        return view('admin.panel', [
            'productos' => Producto::with('serie')
                ->orderBy('tipo')
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function index(): View
    {
        return $this->panel();
    }

    public function create(): View
    {
        return view('admin.crearproducto', [
            'producto' => null,
            'serieSeleccionada' => $this->serieSeleccionada(null),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarProducto($request, true);
        $rutaImagen = $this->guardarImagen($request->file('producto.imagen'));

        try {
            Producto::create([
                ...$datos,
                'slug' => $this->slugUnico($datos['nombre']),
                'imagen' => 'storage/'.$rutaImagen,
            ]);
        } catch (Throwable $error) {
            $this->eliminarArchivo($rutaImagen);

            throw $error;
        }

        return redirect()
            ->route('admin.productos.index')
            ->with('estado', 'El producto se creó correctamente.');
    }

    public function edit(Producto $producto): View
    {
        $producto->load('serie');

        return view('admin.editarproducto', [
            'producto' => $producto,
            'serieSeleccionada' => $this->serieSeleccionada($producto->serie_id),
        ]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $datos = $this->validarProducto($request, false);
        $rutaImagenNueva = null;

        if ($request->hasFile('producto.imagen')) {
            $rutaImagenNueva = $this->guardarImagen($request->file('producto.imagen'));
            $datos['imagen'] = 'storage/'.$rutaImagenNueva;
        }

        $datos['slug'] = $this->slugUnico($datos['nombre'], $producto);
        $rutaImagenAnterior = $producto->imagen;

        try {
            $producto->update($datos);
        } catch (Throwable $error) {
            if ($rutaImagenNueva !== null) {
                $this->eliminarArchivo($rutaImagenNueva);
            }

            throw $error;
        }

        if ($rutaImagenNueva !== null) {
            $this->eliminarImagenDelProducto($rutaImagenAnterior);
        }

        return redirect()
            ->route('admin.productos.index')
            ->with('estado', 'El producto se actualizó correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        if ($producto->detalleCompras()->exists()) {
            return back()->withErrors([
                'producto' => 'No se puede borrar este producto porque tiene compras asociadas. Se conservó el historial.',
            ]);
        }

        $rutaImagen = $producto->imagen;

        try {
            $producto->delete();
        } catch (QueryException $error) {
            if ($producto->detalleCompras()->exists()) {
                return back()->withErrors([
                    'producto' => 'No se puede borrar este producto porque tiene compras asociadas. Se conservó el historial.',
                ]);
            }

            throw $error;
        }

        $this->eliminarImagenDelProducto($rutaImagen);

        return redirect()
            ->route('admin.productos.index')
            ->with('estado', 'El producto se borró correctamente.');
    }

    public function buscarSeries(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ], [
            'q.string' => 'La búsqueda de series debe ser texto.',
            'q.max' => 'La búsqueda no puede superar los 100 caracteres.',
        ]);

        $busqueda = trim($datos['q'] ?? '');
        $series = [];

        if (mb_strlen($busqueda) >= 2) {
            $series = Serie::query()
                ->where('nombre', 'like', '%'.$busqueda.'%')
                ->orderBy('nombre')
                ->limit(10)
                ->get(['id', 'nombre']);
        }

        return response()->json($series);
    }

    /**
     * @return array{
     *     nombre: string,
     *     tipo: string,
     *     orden: int|null,
     *     precio: string,
     *     descripcion: string,
     *     numero: int|null,
     *     numero_paginas: int|null,
     *     stock: int,
     *     serie_id: int|null
     * }
     */
    private function validarProducto(Request $request, bool $imagenObligatoria): array
    {
        $limiteImagen = $imagenObligatoria ? 2048 : 5120;
        $reglas = [
            'producto' => ['required', 'array:nombre,tipo,orden,precio,descripcion,numero,numero_paginas,stock,serie_id,imagen'],
            'producto.nombre' => ['required', 'string', 'min:2', 'max:255'],
            'producto.tipo' => ['required', 'in:'.Producto::TIPO_MANGA.','.Producto::TIPO_COMIC],
            'producto.orden' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
            'producto.precio' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'producto.descripcion' => ['required', 'string', 'min:2'],
            'producto.numero' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'producto.numero_paginas' => ['nullable', 'integer', 'min:1', 'max:4294967295'],
            'producto.stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'producto.serie_id' => ['nullable', 'integer', 'exists:series,id'],
            'producto.imagen' => [
                $imagenObligatoria ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:'.$limiteImagen,
            ],
        ];

        $mensajes = [
            'producto.required' => 'Completá los datos del producto.',
            'producto.array' => 'Los datos enviados no tienen un formato válido.',
            'producto.nombre.required' => 'Ingresá el nombre del producto.',
            'producto.nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'producto.nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'producto.tipo.required' => 'Elegí si el producto es manga o cómic.',
            'producto.tipo.in' => 'El tipo debe ser manga o cómic.',
            'producto.orden.integer' => 'El orden debe ser un número entero.',
            'producto.orden.min' => 'El orden no puede ser negativo.',
            'producto.precio.required' => 'Ingresá el precio.',
            'producto.precio.numeric' => 'El precio debe ser un número.',
            'producto.precio.decimal' => 'El precio puede tener hasta dos decimales.',
            'producto.precio.min' => 'El precio no puede ser negativo.',
            'producto.precio.max' => 'El precio supera el máximo permitido.',
            'producto.descripcion.required' => 'Ingresá una descripción.',
            'producto.descripcion.min' => 'La descripción debe tener al menos 2 caracteres.',
            'producto.numero.integer' => 'El número de tomo debe ser un entero.',
            'producto.numero.min' => 'El número de tomo no puede ser negativo.',
            'producto.numero_paginas.integer' => 'La cantidad de páginas debe ser un entero.',
            'producto.numero_paginas.min' => 'La cantidad de páginas debe ser mayor que cero.',
            'producto.stock.required' => 'Ingresá el stock disponible.',
            'producto.stock.integer' => 'El stock debe ser un número entero.',
            'producto.stock.min' => 'El stock no puede ser negativo.',
            'producto.serie_id.integer' => 'Elegí una serie válida.',
            'producto.serie_id.exists' => 'La serie seleccionada ya no existe. Buscala nuevamente.',
            'producto.imagen.required' => 'Seleccioná una imagen para el producto.',
            'producto.imagen.image' => 'El archivo debe ser una imagen válida.',
            'producto.imagen.mimes' => 'La imagen debe estar en formato JPG, PNG o WebP.',
            'producto.imagen.max' => 'La imagen supera el tamaño máximo permitido.',
        ];

        $datos = $request->validate($reglas, $mensajes)['producto'];

        return [
            'nombre' => trim($datos['nombre']),
            'tipo' => $datos['tipo'],
            'orden' => $datos['orden'] ?? null,
            'precio' => $datos['precio'],
            'descripcion' => $datos['descripcion'],
            'numero' => $datos['numero'] ?? null,
            'numero_paginas' => $datos['numero_paginas'] ?? null,
            'stock' => $datos['stock'],
            'serie_id' => $datos['serie_id'] ?? null,
        ];
    }

    private function guardarImagen(UploadedFile $imagen): string
    {
        $ruta = $imagen->store('productos', 'public');

        if ($ruta === false) {
            throw new RuntimeException('No se pudo guardar la imagen del producto.');
        }

        return $ruta;
    }

    private function eliminarImagenDelProducto(?string $imagen): void
    {
        if ($imagen === null) {
            return;
        }

        $prefijoPublico = 'storage/';
        $ruta = str_starts_with($imagen, $prefijoPublico)
            ? substr($imagen, strlen($prefijoPublico))
            : $imagen;

        if (! str_starts_with($ruta, 'productos/')) {
            return;
        }

        $this->eliminarArchivo($ruta);
    }

    private function eliminarArchivo(string $ruta): void
    {
        if (! Storage::disk('public')->delete($ruta)) {
            throw new RuntimeException('No se pudo borrar un archivo del producto.');
        }
    }

    private function slugUnico(string $nombre, ?Producto $producto = null): string
    {
        $base = Str::slug($nombre) ?: 'producto';
        $base = ctype_digit($base) ? 'producto-'.$base : $base;
        $slug = $base;
        $sufijo = 2;

        while (
            Producto::query()
                ->where('slug', $slug)
                ->when($producto, fn ($consulta) => $consulta->whereKeyNot($producto->getKey()))
                ->exists()
        ) {
            $slug = $base.'-'.$sufijo;
            $sufijo++;
        }

        return $slug;
    }

    private function serieSeleccionada(?int $serieId): ?Serie
    {
        $serieIdAnterior = old('producto.serie_id', $serieId);

        return is_numeric($serieIdAnterior)
            ? Serie::query()->find((int) $serieIdAnterior)
            : null;
    }
}
