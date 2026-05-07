@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; flex-wrap: wrap; gap: 0.75rem;">

        {{-- Results Info --}}
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            @if ($paginator->firstItem())
                Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> results
            @else
                {{ $paginator->count() }} results
            @endif
        </div>

        {{-- Page Links --}}
        <div style="display: flex; align-items: center; gap: 0.25rem;">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: var(--text-light); background: var(--light-bg); border: 1px solid var(--border-color); border-radius: 0.125rem; cursor: not-allowed; opacity: 0.6;">
                    <i class="fas fa-chevron-left" style="font-size: 0.75rem;"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); background: var(--light-surface); border: 1px solid var(--border-color); border-radius: 0.125rem; text-decoration: none; transition: all 0.2s;">
                    <i class="fas fa-chevron-left" style="font-size: 0.75rem;"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-surface);">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 700; color: white; background: var(--primary-color); border: 1px solid var(--primary-color); border-radius: 0.125rem; cursor: default;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); background: var(--light-surface); border: 1px solid var(--border-color); border-radius: 0.125rem; text-decoration: none; transition: all 0.2s;" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); background: var(--light-surface); border: 1px solid var(--border-color); border-radius: 0.125rem; text-decoration: none; transition: all 0.2s;" aria-label="{{ __('pagination.next') }}">
                    <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                </a>
            @else
                <span style="display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: var(--text-light); background: var(--light-bg); border: 1px solid var(--border-color); border-radius: 0.125rem; cursor: not-allowed; opacity: 0.6;">
                    <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                </span>
            @endif

        </div>
    </nav>
@endif
