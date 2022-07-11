@extends('email_template.layout')
@section('content')
    <p>Dear HRD,</p>
    <p style="text-align:justify;">Berikut detail izin karyawan:</p>
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            @for ($a = 0; $a < count($names); $a++)
                <tr>
                    <td style="width:120px;">{{ $a == 0 ? 'Nama' : '' }}</td>
                    <td style="width:10px;">:</td>
                    <td><strong>{{ $names[$a]['name'] . ' ( '. $names[$a]['position'] .' ) ' }}</strong></td>
                </tr>
            @endfor
            <tr>
                <td style="width:120px;">Alasan Keluar</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $data->notes }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Waktu Keluar</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ date('d F Y H:i', strtotime($data->leave_date_time)) }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Dicek Oleh</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ ucwords($checkedBy) }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            <tr>
                <td style="width: 30%">
                    <div>
                        <p style="margin-bottom: 30px !important;">Dibuat Oleh</p>
                        <p style="margin-bottom: 0 !important; border-bottom: 1px solid black;">{{ $data->approvedBy->name }}</p>
                        <p style="margin-top: 0 !important;">{{ $data->approvedBy->position->name }}</p>
                    </div>
                </td>
                <td style="width: 40%"></td>
                <td style="width: 30%">
                    <div>
                        <p style="margin-bottom: 30px !important;">Disetujui Oleh</p>
                        <p style="margin-bottom: 0 !important; border-bottom: 1px solid black;">MASTAHTALIFUN, S.Pd.</p>
                        <p style="margin-top: 0 !important;">MANAGER HRD</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
@endsection