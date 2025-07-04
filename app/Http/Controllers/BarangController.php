<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        $q = $request->query('q');
        $n = intval($request->query('n') ?? 3);
        if ($n <= 0 || $n > 100) {
            $n = 3;
        }

        if (!is_null($q)) {
            $query = $query->where('nama', 'like', "%{$q}%");
        }

        //$items = $query->paginate(3);
        $items = $query->orderBy('id')->cursorPaginate($n);

        return view('barang.index', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(
            'barang.form',
            [
                'action' => route('barang.store'),
                'item' => new Barang()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Barang::create([
            'nama' => $request->input('nama'),
            'barcode' => $request->input('barcode'),
            'satuan' => $request->input('satuan'),
        ]);

        return redirect(route('barang.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $b = Barang::find($id);
        if (!is_null($b)) {
            return view('barang.show', ['item' => $b]);
        }
        return abort(404);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $b = Barang::find($id);
        if (is_null($b)) {
            return abort(404);
        }

        return view(
            'barang.form',
            [
                'action' => route('barang.update', $id),
                'item' => $b
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return DB::transaction(
            function () use ($request, $id) {
                $b = Barang::findOrFail($id); // if (is_null($)) { return abort(404); }
                // pessimistic locking
                //$b = Barang::lockForUpdate()->findOrFail($id);

                // optimistic lock dengan cara check version masih sama
                if ($b->version !== intval($request->input('version'))) {
                    return redirect(route('barang.edit', $id))
                        ->withErrors([
                            'version' => 'Versi barang berubah, tolong refresh',
                        ])
                        ->withInput();
                }

                $b->update([
                    'nama' => $request->input('nama'),
                    'barcode' => $request->input('barcode'),
                    'satuan' => $request->input('satuan'),
                    'version' => $b->version + 1,
                ]);

                return redirect(route('barang.show', $id));
            }
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Barang::find($id)?->delete();

        return redirect(route('barang.index'));
    }
}
