<nav aria-label="Page navigation example">
    @if ($paginator->hasPages())
        <ul class="pagination pagination-circle pagination-outline">
            <li class="page-item first m-1">
                <a href="{{ $paginator->url(1) }}" class="page-link px-0">
                    <i class="ki-duotone ki-double-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                </a>
            </li>

            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Prev</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Prev</span>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">{{ $element }}</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span aria-hidden="true">Next &raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="#" rel="next">
                        <span aria-hidden="true">Next &raquo;</span>
                    </a>
                </li>
            @endif

            <li class="page-item last m-1">
                <a href="{{ $paginator->url($page) }}" class="page-link px-0">
                    <i class="ki-duotone ki-double-right fs-2"><span class="path1"></span><span class="path2"></span></i>
                </a>
            </li>
        </ul>
    @endif
</nav>
