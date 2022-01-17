{!! Form::model($column, [
    'url'     => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="row">
            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="name" class="mb-0 font-weight-normal">Nama</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ old('name') ?? $column->name }}">
                </div>
            </div>

            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="unit_id" class="font-weight-normal mb-0">Satuan</label>
                    <select class="form-control form-control-sm select2" id="unit_id" name="unit_id" style="width: 100%;">
                        <option value=""></option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ $column->unit()->find($unit->id) ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="type_id" class="font-weight-normal mb-0">Jenis</label>
                    <select class="form-control form-control-sm select2" id="type_id" name="type_id" style="width: 100%;">
                        <option value=""></option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ $column->type()->find($type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer p-1">
        <div class="d-flex justify-content-center">
            @if ($column->id)
                <button type="submit" class="btn btn-warning btn-submit btn-xs py-0 px-1 mr-1">UPDATE</button>
            @else
                <button type="submit" class="btn btn-primary btn-submit btn-xs py-0 px-1">SAVE</button>
                <button type="reset" class="btn btn-warning btn-reset btn-xs py-0 px-1 mx-1">RESET</button>
            @endif
            <button type="reset" class="btn btn-danger btn-back btn-xs py-0 px-1">BACK</button>
        </div>
    </div>
{!! Form::close() !!}
