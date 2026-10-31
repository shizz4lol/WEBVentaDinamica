<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Carrito de clientes e invitados.
class CarritoController extends Controller
{
    public function ver(Request $request)
    {
        if (auth('admin')->check()) {
            return redirect()->route('inicio')->withErrors(['carrito' => 'El carrito es solo para clientes.']);
        }

        $cliente = $request->user('web');
        $carrito = $cliente ? $cliente->carrito()->firstOrCreate() : Carrito::deSesion($request->session());
        if ($cliente) {
            $carrito->load('items.producto');
        }

        return view('carrito', ['carrito' => $carrito]);
    }

    public function agregar(Request $request)
    {
        if (auth('admin')->check()) {
            return redirect()->route('inicio')->withErrors(['carrito' => 'El carrito es solo para clientes.']);
        }

        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'cantidad' => ['nullable', 'integer', 'min:1', 'max:99'],
        ], [
            'producto_id.*' => 'Ese producto no existe.',
            'cantidad.*' => 'La cantidad tiene que ser un número entre 1 y 99.',
        ]);
        $cantidad = $datos['cantidad'] ?? 1;

        // Transacción + lockForUpdate: si dos pedidos llegan a la vez, no se pasa del stock.
        $error = DB::transaction(function () use ($request, $datos, $cantidad) {
            $producto = Producto::lockForUpdate()->findOrFail($datos['producto_id']);
            $cliente = $request->user('web');
            $cantidades = $request->session()->get('carrito', []);
            $carrito = $cliente?->carrito()->firstOrCreate();
            $item = $carrito?->items()->firstOrNew(['producto_id' => $producto->id]);
            $nuevaCantidad = ($cliente ? ($item->cantidad ?? 0) : ($cantidades[$producto->id] ?? 0)) + $cantidad;

            if ($producto->stock === 0) {
                return "{$producto->nombre} no tiene stock.";
            }
            if ($nuevaCantidad > $producto->stock) {
                return "Solo quedan {$producto->stock} unidades de {$producto->nombre}.";
            }

            if ($cliente) {
                $item->cantidad = $nuevaCantidad;
                $item->save();
            } else {
                $cantidades[$producto->id] = $nuevaCantidad;
                $request->session()->put('carrito', $cantidades);
            }

            return null;
        });

        if ($error) {
            return back()->withErrors(['carrito' => $error]);
        }

        return back()->with('estado', 'Producto agregado al carrito.');
    }

    public function quitar(Request $request, CarritoItem $item)
    {
        if (auth('admin')->check()) {
            return redirect()->route('inicio')->withErrors(['carrito' => 'El carrito es solo para clientes.']);
        }

        // Un cliente solo puede tocar su propio carrito.
        abort_unless((int) $item->carrito->cliente_id === (int) $request->user()->id, 404);

        $item->delete();

        return redirect()->route('carrito.ver')->with('estado', 'Producto quitado del carrito.');
    }

    public function quitarInvitado(Request $request, int $productoId)
    {
        if (auth('admin')->check()) {
            return redirect()->route('inicio')->withErrors(['carrito' => 'El carrito es solo para clientes.']);
        }
        abort_if(auth('web')->check(), 404);
        $producto = Producto::findOrFail($productoId);
        $cantidades = $request->session()->get('carrito', []);
        unset($cantidades[$producto->id]);
        $request->session()->put('carrito', $cantidades);

        return redirect()->route('carrito.ver')->with('estado', 'Producto quitado del carrito.');
    }
}
