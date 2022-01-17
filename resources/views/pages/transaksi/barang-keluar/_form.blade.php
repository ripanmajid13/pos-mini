{!! Form::model($column, [
    'url'     => $url,
    'method'    => $method,
    'id'        => 'form-action'
]) !!}
    <div class="card-body p-1">
        <div class="row">
            <div class="col-2">
                <div class="form-group mb-1 text-sm">
                    <label for="date" class="font-weight-normal mb-0">Tanggal</label>
                    <div class="input-group date" id="date" data-target-input="nearest">
                        <input type="text" name="date" class="form-control form-control-sm datetimepicker-input date" data-target="#date" data-toggle="datetimepicker" value="{{ $column->date ? date_format(date_create($column->date), "d/m/Y") : date('d/m/Y') }}" />
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group mb-1 text-sm">
                    <label for="item_id" class="font-weight-normal mb-0">Barang</label>
                    <select class="form-control form-control-sm select2" id="item_id" name="item_id" style="width: 100%;">
                        <option value=""></option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ $column->item()->find($item->id) ? 'selected' : '' }}>{{ $item->type->name.' '.$item->name }} ({{ $item->unit->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-1">
                <div class="form-group mb-1 text-sm">
                    <label for="qty" class="mb-0 font-weight-normal">Jumlah</label>
                    <input type="text" name="qty" id="qty" class="form-control form-control-sm" value="{{ old('qty') ?? $column->qty }}">
                </div>
            </div>

            <div class="col-5">
                <div class="form-group mb-1 text-sm">
                    <label for="description" class="mb-0 font-weight-normal">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control form-control-sm" value="{{ old('description') ?? $column->description }}">
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
