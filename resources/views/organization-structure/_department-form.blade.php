<div class="form-group row mb-5">
    <label for="departmentName" class="col-form-label">Nama</label>
    <input type="text" name="name" placeholder="Nama Department" value="{{ isset($data) ? $data->name : '' }}" id="departmentName" class="form-control">
</div>