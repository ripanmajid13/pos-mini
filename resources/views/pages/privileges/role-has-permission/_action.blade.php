<div class="text-center">
    @if ($model->name == 'developer')
        <a href="javascript:;" class="btn btn-secondary btn-sm py-0 px-1 disabled" title="Detail">
            <i class="fas fa-search"></i>
        </a>

        <a href="javascript:;" class="btn btn-secondary btn-sm py-0 px-1 disabled" title="Detail">
            <i class="fas fa-search"></i>
        </a>
    @else
        <a href="{{ $url_show_nav }}" class="btn btn-primary btn-detail btn-sm py-0 px-1" title="Detail Navigtaion">
            <i class="fas fa-search"></i>
        </a>

        @if ($action)
            <a href="{{ $url_show_act }}" class="btn btn-success btn-detail btn-sm py-0 px-1" title="Detail Action">
                <i class="fas fa-search"></i>
            </a>
        @else
            <a href="javascript:;" class="btn btn-secondary btn-sm py-0 px-1 disabled" title="Detail">
                <i class="fas fa-search"></i>
            </a>
        @endif
    @endif
</div>
