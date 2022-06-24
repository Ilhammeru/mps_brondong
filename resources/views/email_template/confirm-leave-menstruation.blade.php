@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>Rany</strong></p>
    <br />
    <p style="text-align:justify;">{{ $employeeName }} telah keluar dari kantor, dan persyaratan sudah di cek oleh {{ $checkedBy }}</p>
    <br />
    <p style="text-align:justify;">Berikut detail izin karyawan:</p>
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            <tr>
                <td style="width:120px;">Nama</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $employeeName }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Divisi</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $data->employee->division->name . ' - ' . $data->employee->position->name }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Alasan Keluar</td>
                <td style="width:10px;">:</td>
                <td><strong>Cuti Haid</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Waktu Keluar</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ date('d F Y H:i', strtotime($data->leave_date_time)) }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br />
    <p>Terimakasih.</p>
@endsection