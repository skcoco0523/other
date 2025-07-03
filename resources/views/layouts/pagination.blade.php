{{-- resources/views/components/pagination.blade.php --}}

@php
    // デフォルト値を設定
    $defaultParams = [
        'paginator' => null,
        'additionalParams' => []
    ];
    // 引き渡されたデータをマージ
    $params = array_merge($defaultParams, get_defined_vars());
    $paginator = $params['paginator'];
    $additionalParams = $params['additionalParams'];
@endphp

@if($paginator)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() . '&' . http_build_query($additionalParams) }}" aria-label="Previous">
                    <span aria-hidden="true">&lt;</span>
                </a>
            </li>
            @php
                $pageRange = 3; // 表示するページ番号の範囲
                $start = max($paginator->currentPage() - floor($pageRange / 2), 1);
                $end = min($start + $pageRange - 1, $paginator->lastPage());
                if ($end - $start < $pageRange - 1) {
                    $start = max($end - $pageRange + 1, 1);
                }
            @endphp
            @if($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) . '&' . http_build_query($additionalParams) }}">1</a>
                </li>
                @if($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif
            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $paginator->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) . '&' . http_build_query($additionalParams) }}">{{ $i }}</a>
                </li>
            @endfor
            @if($end < $paginator->lastPage())
                @if($end < $paginator->lastPage() - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) . '&' . http_build_query($additionalParams) }}">{{ $paginator->lastPage() }}</a>
                </li>
            @endif
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() . '&' . http_build_query($additionalParams) }}" aria-label="Next">
                    <span aria-hidden="true">&gt;</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
