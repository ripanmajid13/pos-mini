@if ($column->id == 1)
    <form id="form-action" action="{{ $action }}" method="POST" role="form">
        @csrf
        @method('PUT')
        <div class="card-body p-1">
            <div class="form-group mb-1">
                <label for="description" class="font-weight-normal mb-0 text-sm">Title</label>
                <input type="text" class="form-control form-control-sm" id="description" name="description" value="{{ $column->description }}">
            </div>
        </div>

        <div class="card-footer p-1">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-warning btn-submit btn-xs py-0 px-1 mr-1">UPDATE</button>
                <button type="reset" class="btn btn-danger btn-back btn-xs py-0 px-1">BACK</button>
            </div>
        </div>
    </form>
@elseif ($column->id == 2)
    <form id="form-action" action="{{ $action }}" method="POST" role="form">
        @csrf
        @method('PUT')
        <div class="card-body p-1">
            <div class="form-group mb-1">
                <label for="description" class="font-weight-normal mb-0 text-sm">Copyright</label>
                <input type="text" class="form-control form-control-sm" id="description" name="description" value="{{ $column->description }}">
            </div>
        </div>

        <div class="card-footer p-1">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-warning btn-submit btn-xs py-0 px-1 mr-1">UPDATE</button>
                <button type="reset" class="btn btn-danger btn-back btn-xs py-0 px-1">BACK</button>
            </div>
        </div>
    </form>
@elseif ($column->id == 3)
    <form id="form-action-image" action="{{ $action }}" method="POST" role="form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body p-1">
            <div class="form-group mb-1">
                <label for="description" class="font-weight-normal mb-0 text-sm">Application Image</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control form-control-sm" id="description" name="description">
                        <label class="custom-file-label" for="description">Choose file</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer p-1">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-warning btn-submit btn-xs py-0 px-1 mr-1">UPDATE</button>
                <button type="reset" class="btn btn-danger btn-back btn-xs py-0 px-1">BACK</button>
            </div>
        </div>
    </form>
@endif
