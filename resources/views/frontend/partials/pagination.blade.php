@if ($paginator->lastPage() > 1)
    <?php $previousPage = ($paginator->currentPage() > 1) ? $paginator->currentPage() - 1 : 1; ?>
    <ul class="pagination">
        <li><a class="{{ ($paginator->currentPage() == 1) ? 'disabled' : '' }}" href="{{ $paginator->url($previousPage) }}"><span class="fa fa-angle-left"></span></a></li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li>
                <a class="{{ ($paginator->currentPage() == $i) ? 'active' : '' }}" href="{{ $paginator->url($i) }}">
                    <span>{{ $i }}</span>
                </a>
            </li>
        @endfor
        <li><a class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'disabled' : '' }}" href="{{ $paginator->url($paginator->currentPage()+1) }}"><span class="fa fa-angle-right"></span></a></li>
    </ul>
    <!-- pagination -->
@else
    <ul class="pagination">
        <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
        <li><a class="active" href="#">1</a></li>
        <li><a href="#"><span class="fa fa-angle-right"></span></a></li>
    </ul>
    <!-- pagination -->
@endif
