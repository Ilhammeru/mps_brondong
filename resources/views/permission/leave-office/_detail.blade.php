<div class="row">
    <div class="col">
        <div class="text-center">
            <h3 class="mb-5">{{ $data->ticket_code }}</h3>
            @for($a = 0; $a < count($employee); $a++)
            <p class="mb-0">
                <b>{{ ucwords($employee[$a]['name']) . ' ( '. $employee[$a]['position'] .' ) ' }}</b>
            </p>
            @endfor
            <div class="qrcode d-flex align-items-center justify-content-center" style="margin: 20px 0 20px 0;">
                {!! QrCode::size(250)->generate(url('/leave-office/confirm/br/' . $data->id)); !!}
            </div>
            <p class="mb-1">
                <b>{{ date('d F Y H:i', strtotime($data->leave_date_time)) }}</b>
            </p>
            <p class="mb-1">
                <b>{{ $data->notes }}</b>
            </p>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 40px;">
    <div class="col">
        <div class="text-end">
            <p style="font-size: 10px; color: #A3A3A3;">
                <i>Approved by {{ $data->approvedBy->name }}</i>
            </p>
        </div>
    </div>
</div>