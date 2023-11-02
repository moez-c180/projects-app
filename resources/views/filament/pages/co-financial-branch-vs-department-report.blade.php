<x-filament::page>
    {{-- @dd($data); --}}
    {{-- {{$this->table}} --}}
    <table class="border rounded p-3 w-full bg-white">
        <tbody>
            @foreach($data as $row)
                <tr class="border-b py-2">
                    @foreach($row as $cell)
                        <td class="border p-2">{{$cell}}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
