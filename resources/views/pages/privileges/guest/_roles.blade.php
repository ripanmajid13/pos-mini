{!! Form::model($column, [
    'url'     => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="row">
            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="name" class="mb-0 font-weight-normal">Name</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ $column->name }}" readonly>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="email" class="mb-0 font-weight-normal">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ $column->email }}" readonly>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="username" class="mb-0 font-weight-normal">Username</label>
                    <input type="text" name="username" id="username" class="form-control form-control-sm" value="{{ $column->username }}" readonly>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-1 text-sm">
                    <label for="roles" class="mb-0 font-weight-normal">Roles</label>
                    <select name="roles[]" id="roles" class="form-control form-control-sm select2" multiple>
                        @foreach($roles as $role)
                            <option {{ $column->roles()->find($role->id) ? "selected" : "" }} value="{{ $role->id }}">{{ ucwords($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer p-1">
        <div class="d-flex justify-content-center">
            <button type="reset" class="btn btn-danger btn-back btn-xs py-0 px-1">BACK</button>
            <button type="submit" class="btn btn-info btn-xs btn-submit py-0 px-1 ml-1">TRANSFER TO USER</button>
        </div>
    </div>
{!! Form::close() !!}
