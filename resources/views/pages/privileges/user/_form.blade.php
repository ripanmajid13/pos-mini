{!! Form::model($column, [
    'url'     => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-1 text-sm">
                            <label for="name" class="mb-0 font-weight-normal">Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ old('name') ?? $column->name }}">
                        </div>

                        <div class="form-group mb-1 text-sm">
                            <label for="username" class="mb-0 font-weight-normal">Username</label>
                            <input type="text" name="username" id="username" class="form-control form-control-sm" value="{{ old('username') ?? $column->username }}">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group mb-1 text-sm">
                            <label for="email" class="mb-0 font-weight-normal">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ old('email') ?? $column->email }}">
                        </div>

                        <div class="form-group mb-1 text-sm">
                            <label for="password" class="mb-0 font-weight-normal">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-sm" value="{{ old('password') }}" @isset($column->id) placeholder="Kosongkan jika password tidak diubah" @endisset>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
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
            @if ($column->id)
                <button type="submit" class="btn btn-warning btn-submit btn-xs py-0 px-1 mr-1">UPDATE</button>
            @else
                <button type="submit" class="btn btn-primary btn-submit btn-xs py-0 px-1">SAVE</button>
                <button type="reset" class="btn btn-warning btn-reset btn-xs py-0 px-1 mx-1">RESET</button>
            @endif

            <button type="reset" class="btn btn-back btn-danger btn-xs py-0 px-1">BACK</button>
        </div>
    </div>
{!! Form::close() !!}
