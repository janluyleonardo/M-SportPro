<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Producto eliminado.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ProductsImport, $request->file('file'));

        return back()->with('success', 'Productos importados correctamente.');
    }

    public function downloadTemplate()
    {
        $headers = ['Nombre', 'Descripcion', 'Precio', 'Stock'];
        $data = [
            ['Uniforme Titular', 'Camiseta y pantaloneta', 85000, 20],
            ['Balon Futbol', 'Talla 5 oficial', 120000, 10],
        ];

        return \Maatwebsite\Excel\Facades\Excel::download(new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $headers;
            private $data;
            public function __construct($headers, $data) { $this->headers = $headers; $this->data = $data; }
            public function collection() { return collect($this->data); }
            public function headings(): array { return $this->headers; }
        }, 'plantilla_productos.xlsx');
    }
}
