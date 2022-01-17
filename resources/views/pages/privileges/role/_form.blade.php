{!! Form::model($column, [
    'url'     => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="row">
            <div class="form-group mb-1 col-6 text-sm">
                <label for="name" class="mb-0 font-weight-normal">Name</label>
                <input type="text" name="name" id="name" class="form-control form-control-sm text-capitalize" value="{{ old('name') ?? $column->name }}">
            </div>

            <div class="form-group mb-1 col-6 text-sm">
                <label for="name" class="mb-0 font-weight-normal">Guard Name</label>
                <input type="text" name="guard_name" id="guard_name" class="form-control form-control-sm" placeholder='default to "web"'  value="{{ old('guard_name') ?? $column->guard_name }}">
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
