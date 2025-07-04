<x-layout title="Aplikasi saya">
        <h1>Barang:</h1>
        <div>
            <a href="{{ route('barang.create') }}">Tambah Barang Baru</a>
        </div>
        <div>
            <form method="get" action="{{ route('barang.index') }}">
                <x-input type="text" name="q" placeholder="Cari barang"
                    value="{{ app('request')->query('q') }}" />
                <select name="n">
                    <option value="3" {{ request()->query('n') == 3 ? 'selected' : ''}}>
                        3
                    </option>
                    <option value="5" {{ request()->query('n') == 5 ? 'selected' : ''}}>
                        5
                    </option>
                    <option value="10" {{ request()->query('n') == 10 ? 'selected' : ''}}>
                        10
                    </option>
                </select>
                <x-input type="submit" value="Filter" />
            </form>
        </div>
        <table>
            <tr>
                <td>Nama</td>
                <td>Barcode</td>
                <td>Satuan</td>
                <td>&nbsp;</td>
            </tr>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->nama  }}</td>
                <td>{{ $item->barcode  }}</td>
                <td>{{ $item->satuan}}</td>
                <td>
                    <a href="{{ route('barang.show', $item->id) }}">Show</a>
                </td>
            </tr>
        @endforeach
            <tr>
                <td colspan="4">{{ $items->appends(request()->query())->links() }}</td>
            </tr>
        </table>
</x-layout>



