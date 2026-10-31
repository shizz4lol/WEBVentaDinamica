@php
    $esEdicion = $producto !== null;
    $accion = $esEdicion
        ? route('admin.productos.update', $producto)
        : route('admin.productos.store');
@endphp

<form action="{{ $accion }}" method="post" enctype="multipart/form-data" class="panel mx-auto grid max-w-4xl gap-6 p-5 sm:p-8">
    @csrf
    @if ($esEdicion)
        @method('PUT')
    @endif

    <div>
        <label for="producto-nombre" class="etiqueta">Nombre</label>
        <input
            id="producto-nombre"
            name="producto[nombre]"
            value="{{ old('producto.nombre', $producto?->nombre) }}"
            maxlength="255"
            required
            class="campo"
            @error('producto.nombre') aria-invalid="true" aria-describedby="error-producto-nombre" @enderror
        >
        @error('producto.nombre')
            <p id="error-producto-nombre" role="alert" class="error-campo">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="producto-tipo" class="etiqueta">Tipo</label>
            <select id="producto-tipo" name="producto[tipo]" required class="campo">
                <option value="">Elegí un tipo</option>
                <option value="manga" @selected(old('producto.tipo', $producto?->tipo) === 'manga')>Manga</option>
                <option value="comic" @selected(old('producto.tipo', $producto?->tipo) === 'comic')>Cómic</option>
            </select>
            @error('producto.tipo')
                <p role="alert" class="error-campo">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="producto-precio" class="etiqueta">Precio (ARS)</label>
            <input
                id="producto-precio"
                name="producto[precio]"
                type="number"
                value="{{ old('producto.precio', $producto?->precio) }}"
                min="0"
                max="99999999.99"
                step="0.01"
                required
                class="campo"
            >
            @error('producto.precio')
                <p role="alert" class="error-campo">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-3">
        <div>
            <label for="producto-orden" class="etiqueta">Orden en el catálogo (opcional)</label>
            <input
                id="producto-orden"
                name="producto[orden]"
                type="number"
                value="{{ old('producto.orden', $producto?->orden) }}"
                min="0"
                step="1"
                class="campo"
            >
            @error('producto.orden')
                <p role="alert" class="error-campo">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="producto-numero" class="etiqueta">Número de tomo (opcional)</label>
            <input
                id="producto-numero"
                name="producto[numero]"
                type="number"
                value="{{ old('producto.numero', $producto?->numero) }}"
                min="0"
                step="1"
                class="campo"
            >
            @error('producto.numero')
                <p role="alert" class="error-campo">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="producto-numero-paginas" class="etiqueta">Páginas (opcional)</label>
            <input
                id="producto-numero-paginas"
                name="producto[numero_paginas]"
                type="number"
                value="{{ old('producto.numero_paginas', $producto?->numero_paginas) }}"
                min="1"
                step="1"
                class="campo"
            >
            @error('producto.numero_paginas')
                <p role="alert" class="error-campo">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="producto-stock" class="etiqueta">Stock</label>
        <input
            id="producto-stock"
            name="producto[stock]"
            type="number"
            value="{{ old('producto.stock', $producto?->stock ?? 0) }}"
            min="0"
            step="1"
            required
            class="campo"
        >
        @error('producto.stock')
            <p role="alert" class="error-campo">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="producto-descripcion" class="etiqueta">Descripción</label>
        <textarea
            id="producto-descripcion"
            name="producto[descripcion]"
            rows="5"
            required
            class="campo"
            @error('producto.descripcion') aria-invalid="true" aria-describedby="error-producto-descripcion" @enderror
        >{{ old('producto.descripcion', $producto?->descripcion) }}</textarea>
        @error('producto.descripcion')
            <p id="error-producto-descripcion" role="alert" class="error-campo">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <span class="etiqueta">Serie (opcional)</span>
        <div
            data-buscador-series
            data-url-busqueda="{{ route('admin.series.buscar') }}"
            data-serie-id="{{ $serieSeleccionada?->id }}"
            data-serie-nombre="{{ $serieSeleccionada?->nombre }}"
            data-tiene-error="{{ $errors->has('producto.serie_id') ? 'true' : 'false' }}"
        ></div>
        @error('producto.serie_id')
            <p id="error-producto-serie" role="alert" class="error-campo">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <span class="etiqueta">Imagen</span>
        <div
            data-selector-imagen
            data-nombre-campo="producto[imagen]"
            data-imagen-actual="{{ $producto?->imagen ? asset($producto->imagen) : '' }}"
            data-obligatoria="{{ $esEdicion ? 'false' : 'true' }}"
            data-tiene-error="{{ $errors->has('producto.imagen') ? 'true' : 'false' }}"
        ></div>
        @error('producto.imagen')
            <p id="error-producto-imagen" role="alert" class="error-campo">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-texto-suave">
            JPG, PNG o WebP. Máximo {{ $esEdicion ? '5' : '2' }} MB{{ $esEdicion ? '; si no elegís otra, se conserva la imagen actual.' : '.' }}
        </p>
    </div>

    <div class="flex flex-wrap justify-end gap-3 border-t border-borde pt-5">
        <a href="{{ route('admin.productos.index') }}" class="btn btn-secundario">Cancelar</a>
        <button type="submit" class="btn btn-primario">{{ $esEdicion ? 'Guardar cambios' : 'Crear producto' }}</button>
    </div>
</form>
