{!! Form::model($column, [
    'url'     => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="form-group">
            <label for="role" class="mb-0 font-weight-normal text-sm">Role</label>
            <input type="text" class="form-control form-control-sm text-capitalize" value="{{ $column->name }}" disabled style="background-color: #fff;">
        </div>

        @if ($slug)
            <div class="form-group">
                <label for="navigations" class="mb-0 font-weight-normal text-sm">Navigation</label>
                <select multiple class="form-control select2" name="navigations[]" id="navigations">
                    @foreach ($navigations as $navigation)
                        <option value="{{ $navigation->permissions->first()['id'] }}" {{ $column->permissions()->find($navigation->permissions->first()['id']) ? "selected" : "" }}>
                            @isset($navigation->parent['name'])
                                {{ $navigation->parent['name'].' '.$navigation->name }}
                            @else
                                {{ $navigation->name }}
                            @endisset
                        </option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="form-group">
                <label for="navigations" class="mb-0 font-weight-normal text-sm">Navigation</label>
                <select multiple class="form-control select2" id="navigations" disabled>
                    @foreach ($navigations as $navigation)
                        <option value="{{ $navigation->permissions->first()['id'] }}" {{ $column->permissions()->find($navigation->permissions->first()['id']) ? "selected" : "" }}>
                            @isset($navigation->parent['name'])
                                {{ $navigation->parent['name'].' '.$navigation->name }}
                            @else
                                {{ $navigation->name }}
                            @endisset
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="actions" class="mb-0 font-weight-normal text-sm">Action</label>
                <select multiple class="form-control select2" name="actions[]" id="actions">
                    @foreach ($navigations as $item)
                        @if ($column->permissions()->find($item->permissions->first()['id']))
                            @if ($item->permissions->first()->children->count())
                                @foreach ($item->permissions->first()->children as $ch)
                                    <option value="{{ $ch->id }}" {{ $column->permissions()->find($ch->id) ? "selected" : "" }}>
                                        {{ ucwords(str_replace('-', ' ', $ch->name)) }}
                                    </option>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <div class="card-footer p-1">
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-warning btn-xs py-0 px-1">UPDATE</button>
            <button type="button" class="btn btn-back btn-danger btn-xs py-0 px-1 ml-1">BACK</button>
        </div>
    </div>
{!! Form::close() !!}

{{--
<div class="card-body p-1">
    <div class="row">
        <div class="col-5">
            <div class="card card-primary">
                <div class="card-header  p-1">
                    <h3 class="card-title text-sm">Navigation</h3>
                </div>

                <form action="{{ $actionUpdateNavigation }}" role="form" method="post" id="form-nav">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-1">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" value="{{ $role->name }}" disabled style="background-color: #fff;">
                        </div>

                        <div class="form-group">
                            <label for="navigations">Navigation</label>
                            <select multiple class="form-control select2" name="navigations[]" id="navigations">
                                @foreach ($navigations as $navigation)
                                    <option value="{{ $navigation->permissions->first()['id'] }}" {{ $role->permissions()->find($navigation->permissions->first()['id']) ? "selected" : "" }}>
                                        @isset($navigation->parent['name'])
                                            {{ $navigation->parent['name'].' '.$navigation->name }}
                                        @else
                                            {{ $navigation->name }}
                                        @endisset
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-footer p-1">
                        <button type="submit" class="btn btn-warning btn-xs py-0 px-1 mr-1">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card-footer text-center p-1">
    <button type="button" class="btn btn-form-back btn-danger btn-xs py-0 px-1 mr-1">BACK</button>
</div> --}}
