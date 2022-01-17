<div class="text-center">
    @can($canEdit)
        @if ($model->hasRole('developer'))
            @if ($model->id == auth()->user()->id)
                <a href="{{ $urlEdit }}" class="btn btn-warning btn-form btn-xs py-0 px-1" title="Edit">
                    <i class="fas fa-user-edit"></i>
                </a>
            @else
                <button type="button" class="btn btn-secondary btn-xs disabled">
                    <i class="fas fa-user-edit"></i>
                </button>
            @endif
        @else
            <a href="{{ $urlEdit }}" class="btn btn-warning btn-form btn-xs py-0 px-1" title="Edit">
                <i class="fas fa-user-edit"></i>
            </a>
        @endif
    @endcan

    @can($canDelete)
        @if ($model->hasRole('developer'))
            @if ($model->id == auth()->user()->id)
                <a href="{{ $urlDestroy }}" class="btn btn-danger btn-delete btn-xs py-0 px-1" data-text="{{ $model->name }}" title="Delete">
                    <i class="fas fa-trash"></i>
                </a>
            @else
                <button type="button" class="btn btn-secondary btn-xs disabled">
                    <i class="fas fa-trash"></i>
                </button>
            @endif
        @else
            <a href="{{ $urlDestroy }}" class="btn btn-danger btn-delete btn-xs py-0 px-1" data-text="{{ $model->name }}" title="Delete">
                <i class="fas fa-trash"></i>
            </a>
        @endif
    @endcan
</div>
