{!! Form::model($column, [
    'url'       => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="row">
            <div class="col-9">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group mb-1 text-sm">
                            <label for="name" class="mb-0 font-weight-normal">Nama</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ old('name') ?? $column->name }}">
                        </div>

                        <div class="form-group mb-1 text-sm">
                            <label for="hp" class="mb-0 font-weight-normal">No Handphone</label>
                            <input type="text" name="hp" id="hp" class="form-control form-control-sm" value="{{ old('hp') ?? $column->hp }}">
                        </div>

                        <div class="form-group mb-1 text-sm">
                            <label for="image_logo" class="font-weight-normal mb-0">Logo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control form-control-sm" id="image_logo" name="image_logo" accept=".jpeg, .jpg, .png">
                                    <label class="custom-file-label" for="image_logo">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group mb-1 text-sm">
                            <label for="address" class="mb-0 font-weight-normal">Alamat</label>
                            <textarea class="form-control form-control-sm" rows="6" id="address" name="address" style="resize: none;">{{ old('name') ?? $column->address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 text-sm">
                <label for="address" class="mb-0 font-weight-normal">Preview Logo</label>
                <img src="{{ asset('images/default/picture.png') }}" alt="Logo" class="img-rounded img-fluid img-thumbnail" style="height: 153px; width: 255px;">
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
