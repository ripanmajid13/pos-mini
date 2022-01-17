<div class="text-center">
    @can($canEdit)
        @if ($model->name == 'developer')
            <a href="javascript:;" class="btn btn-secondary btn-xs py-0 px-1 disabled" title="Detail">
                <i class="fas fa-user-edit"></i>
            </a>
        @else
            <a href="{{ $urlEdit }}" class="btn btn-warning btn-form btn-xs py-0 px-1" title="Edit">
                <i class="fas fa-user-edit"></i>
            </a>
        @endif
    @endcan

    @can($canDelete)
        @if ($model->name == 'developer')
            <a href="javascript:;" class="btn btn-secondary btn-xs py-0 px-1 disabled" title="Detail">
                <i class="fas fa-trash"></i>
            </a>
        @else
            <a href="{{ $urlDestroy }}" class="btn btn-danger btn-delete btn-xs py-0 px-1" data-text="{{ $model->name }}" title="Delete">
                <i class="fas fa-trash"></i>
            </a>
        @endif
    @endcan
</div>
