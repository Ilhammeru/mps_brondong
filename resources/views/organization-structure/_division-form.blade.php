<div class="form-group mb-5 row">
    <label for="divisionName" class="col-form-label">Nama</label>
    <input type="text" class="form-control" id="divisionName" name="name" value="{{ isset($division) ? $division->name : '' }}">
</div>
<div class="form-group mb-5 row">
    <label for="departmentId" class="col-form-label">Department</label>
    <select name="department_id" id="departmentId" class="form-control form-select">
        <option value="">- Pilih Department -</option>
        @foreach ($department as $item)
            <option value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
        @endforeach
    </select>
</div>