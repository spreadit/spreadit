@if ($paginator->getLastPage() > 1)
<menu class="pagination">
    <li>
        @if ($paginator->getCurrentPage() > 1)
            <a href="{{ $paginator->getUrl($paginator->getCurrentPage()-1) }}">previous</a>
        @else
            previous
        @endif
    </li>
    <li>
        @if ($paginator->getCurrentPage() < $paginator->getLastPage())
            <a href="{{ $paginator->getUrl($paginator->getCurrentPage()+1) }}">next</a>
        @else
            next
        @endif
    </li>
</menu>    
@endif
