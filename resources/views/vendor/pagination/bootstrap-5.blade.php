@if ($paginator->hasPages())
<div class="pagination-wrap">
    <div class="pagination-info">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }}
        of {{ $paginator->total() }} results
    </div>
    <ul class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-left" style="font-size:.7rem;"></i></span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="fas fa-chevron-left" style="font-size:.7rem;"></i>
                </a>
            </li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <i class="fas fa-chevron-right" style="font-size:.7rem;"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i></span>
            </li>
        @endif
    </ul>
</div>
@endif
