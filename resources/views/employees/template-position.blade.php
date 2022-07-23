<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; text-align: center;">Jabatan</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr style="border: 1px solid #000;">
            <th style="border: 1px solid #000;">No</th>
            <th style="border: 1px solid #000;">Nama</th>
        </tr>
    </thead>
    <tbody>
        @php
            $x = 1;
        @endphp
        @foreach ($position as $item)
            <tr style="border: 1px solid #000;">
                <td style="border: 1px solid #000;">{{ $x }}</td>
                <td style="border: 1px solid #000;">{{ $item->name }}</td>
            </tr>
            @php
                $x++;
            @endphp
        @endforeach
    </tbody>
</table>