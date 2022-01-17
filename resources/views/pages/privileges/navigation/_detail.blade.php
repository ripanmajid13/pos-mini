<div class="row invoice-info p-1">
    <div class="col-4 border-right border-info">
        <div class="row">
            <div class="col-sm-3 invoice-col text-xs">
                <div class="d-flex justify-content-between">
                    <span>Name</span>
                    <span class="text-right">:</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Parent</span>
                    <span>:</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Url</span>
                    <span>:</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Icon</span>
                    <span>:</span>
                </div>

               <div class="d-flex justify-content-between">
                    <span>Position</span>
                    <span>:</span>
                </div>
            </div>

            <div class="col-sm-9 invoice-col text-xs">
                <p class="mb-0">{{ $column->name }}</p>
                <p class="mb-0">{{ $column->parent_id ? $column->parent->name : 'NULL' }}</p>
                <p class="mb-0">/{{ $column->url }}</p>
                <p class="mb-0">
                    @if ($column->icon)
                        <i class="{{ $column->icon }}"></i>
                    @else
                        NULL
                    @endif
                </p>
                <p class="mb-0">{{ $column->position }}</p>
            </div>
        </div>
    </div>

    <div class="col-sm-4 invoice-col border-right border-info">
        <div class="card card-primary mb-0">
            <div class="card-header text-sm p-1">
                <h3 class="card-title">Form Add Action</h3>
            </div>

            <form id="form-action" role="form" action="{{ $action }}">
                @csrf
                <div class="card-body p-1">
                    <div class="form-group">
                    <label for="name" class="font-weight-normal text-xs mb-0">Name</label>
                    <input type="text" class="form-control form-control-xs" id="name" name="name">
                    </div>
              </div>

              <div class="card-footer p-1">
                <div class="d-flex justify-content-center">
                    <button type="reset" class="btn btn-danger btn-xs py-0 px-1 btn-back">BACK</button>
                    &nbsp;
                    <button type="reset" id="action-reset" class="btn btn-warning btn-xs py-0 px-1">RESET</button>
                    &nbsp;
                    <button type="submit" id="action-submit" class="btn btn-primary btn-xs py-0 px-1">SAVE</button>
                </div>
              </div>
            </form>
        </div>
    </div>

    <div class="col-4 table-responsive">
        <table id="actionTable" class="table table-sm table-bordered table-hover text-xs dt-responsive nowrap mb-0" style="width:100%; margin-top: 0 !important; margin-bottom: 0 !important;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Guard</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
