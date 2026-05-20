@if ($paginator->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;padding:.75rem 1.25rem;border-top:1px solid #e2e8f0;margin-top:0;">

    {{-- Info text --}}
    <div style="font-size:.78rem;color:#64748b;font-weight:500;">
        Showing <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
        to <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
        of <strong>{{ $paginator->total() }}</strong> results
    </div>

    {{-- Page buttons --}}
    <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1.5px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;font-size:.7rem;cursor:not-allowed;">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;color:#475569;font-size:.7rem;text-decoration:none;transition:all .15s;"
               onmouseover="this.style.borderColor='#2563eb';this.style.color='#2563eb';"
               onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569';">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 6px;border-radius:8px;border:1.5px solid #e2e8f0;background:#f8fafc;color:#94a3b8;font-size:.8rem;font-weight:600;">
                    {{ $element }}
                </span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 6px;border-radius:8px;background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;font-size:.8rem;font-weight:700;border:none;box-shadow:0 2px 8px rgba(37,99,235,.3);">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 6px;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;color:#475569;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .15s;"
                           onmouseover="this.style.borderColor='#2563eb';this.style.color='#2563eb';this.style.background='#eff6ff';"
                           onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569';this.style.background='#fff';">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;color:#475569;font-size:.7rem;text-decoration:none;transition:all .15s;"
               onmouseover="this.style.borderColor='#2563eb';this.style.color='#2563eb';"
               onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569';">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1.5px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;font-size:.7rem;cursor:not-allowed;">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </div>
</div>
@endif
