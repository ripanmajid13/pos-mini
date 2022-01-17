<div class="text-center">
    @if ($model['url'])
        <a href="{{ $urlDetail }}" class="btn btn-info btn-xs btn-detail py-0 px-1" title="Detail">
            <i class="fas fa-search-plus"></i>
        </a>
    @else
        <button type="button" class="btn btn-secondary py-0 px-1 btn-xs disabled">
            <i class="fas fa-search-plus"></i>
        </button>
    @endif

    <a href="{{ $urlEdit }}" data-form="{{ $data }}" class="btn btn-warning btn-xs {{ $model['edit'] }} py-0 px-1" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <a href="{{ $urlDelete }}" class="btn btn-danger btn-delete btn-delete btn-xs py-0 px-1" data-text="{{ $model['name'] }}" title="Delete">
        <i class="fas fa-trash"></i>
    </a>
</div>
