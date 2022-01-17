<div class="text-center">
    @can($canRoles)
        <a href="{{ $urlRoles }}" class="btn btn-info btn-roles btn-xs py-0 px-1" title="Add Roles">
           <i class="fas fa-people-arrows"></i>
        </a>
    @endcan

    @can($canEdit)
        <a href="{{ $urlEdit }}" class="btn btn-warning btn-form btn-xs py-0 px-1" title="Edit">
            <i class="fas fa-user-edit"></i>
        </a>
    @endcan

    @can($canDelete)
        <a href="{{ $urlDestroy }}" class="btn btn-danger btn-delete btn-xs py-0 px-1" data-text="{{ $text }}" title="Delete">
            <i class="fas fa-trash"></i>
        </a>
    @endcan
</div>
