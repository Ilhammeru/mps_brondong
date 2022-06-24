<div class="row mb-5">
    <div class="col-md-6">
        <table class="table">
            <tbody>
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td>
                        <b>{{ count($data) }}x dalam 1 bulan berjalan</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<table class="table table-striped">
    <thead class="table-primary">
        <tr class="text-center">
            <th>Tanggal</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr class="text-center">
                <td>
                    <b>{{ date('d F Y', strtotime($item->leave_date_time)) }}</b>
                </td>
                <td class="pe-5 ps-5">
                    <div class="card card-flush bg-secondary">
                        <div class="card-body ps-5 pt-1 pb-1 pe-1">
                            <table class="table">
                                <tbody>
                                    <tr class="text-start">
                                        <td>Alasan</td>
                                        <td>:</td>
                                        <td>{{ $item->notes }}</td>
                                    </tr>
                                    <tr class="text-start">
                                        <td>Tanggal Keluar</td>
                                        <td>:</td>
                                        <td>{{ date('d/m/Y', strtotime($item->leave_date_time)) }}</td>
                                    </tr>
                                    <tr class="text-start">
                                        <td>Jam Keluar</td>
                                        <td>:</td>
                                        <td>{{ date('H:i:s', strtotime($item->leave_date_time)) }}</td>
                                    </tr>
                                    <tr class="text-start">
                                        <td>Diizinkan Oleh</td>
                                        <td>:</td>
                                        <td>
                                            @if ($item->checkedBy == NULL)
                                                <span class="badge badge-info">Belum Diizinkan</span>
                                            @else
                                                {{ ucwords($item->checkedBy->name) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="text-start">
                                        <td>Atas Persetujuan</td>
                                        <td>:</td>
                                        <td>{{ ucwords($item->approvedBy->name) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>