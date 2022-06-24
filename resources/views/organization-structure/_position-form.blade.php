<div class="form-group mb-5 row">
    <label for="positionName" class="col-form-label">Nama</label>
    <input type="text" class="form-control" id="positionName" name="name" value="{{ isset($data) ? $data->name : '' }}">
</div>
<div class="form-group mb-5 row">
    <label for="divisionId" class="col-form-label">Divisi</label>
    <select name="division_id" id="divisionId" class="form-select form-control">
        <option value="">- Pilih Divisi -</option>
        @foreach ($division as $item)
            <option {{ !isset($data) ? '' : ($data->division_id == $item->id ? 'selected' : '') }} value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
    </select>
</div>