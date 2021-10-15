<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/2/10
 * Time: 14:24
 */

@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>{{ __('Previous page') }}</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('Previous page') }}</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Next page') }}</a></li>
        @else
            <li class="disabled"><span>{{ __('Next page') }}</span></li>
        @endif
    </ul>
    </nav>
@endif

{{--<nav aria-label="Page navigation">--}}
                                {{--<ul class="pagination">--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">Previous</a></li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">1</a></li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">2</a></li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">3</a></li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">Next</a></li>--}}
                                {{--</ul>--}}
                            {{--</nav>--}}