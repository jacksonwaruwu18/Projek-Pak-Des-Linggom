<x-layout>
        <h1>Form Barang</h1>
        <form method="post" action="{{ $action }}">
            @csrf
            <div>
                <label for="barcode">Barcode:</label>
                <x-input type="text" name="barcode" value="{{ $item->barcode }}" />
            </div>
            <div>
                <label for="barcode">Nama Barang:</label>
                <x-input type="text" name="nama" value="{{ $item->nama }}" />
            </div>
            <div>
                <label for="satuan">Satuan:</label>
                <x-input type="text" name="satuan" value="{{ $item->satuan}}" />
            </div>
            <input type="text" name="version" value="{{ $item->version }}" />
            @error('version')
                <div class="text-red">{{ $errors->first('version') }}</div>
            @enderror
            <div>
                <x-input type="submit" value="Simpan" />
            </div>
        </form>
</x-layout>
