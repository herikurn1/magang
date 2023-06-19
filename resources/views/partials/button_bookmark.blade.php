@if (App\Services\BookmarkService::check_bookmarks(Request::segment(1).'/'.Request::segment(2)))
    <button id="btn_bookmark" class="btn btn-link text-warning" value="unmarked" data-toggle="tooltip" data-placement="bottom" title="Bookmark this page.">
        <h5 class="m-0"><i class="fas fa-bookmark"></i></h5>
    </button>
@else
    <button id="btn_bookmark" class="btn btn-link text-secondary" value="marked" data-toggle="tooltip" data-placement="bottom" title="Bookmark this page.">
        <h5 class="m-0"><i class="far fa-bookmark"></i></h5>
    </button>
@endif