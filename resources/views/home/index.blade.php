@extends("layouts.app-master")
<style>
    #search {
        padding-top: 10px
    }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        position: relative;
    }

    .text-truncate-2 .read-more {
        position: absolute;
        right: 0;
        bottom: 0;
        background: white;
        padding-left: 5px;
    }

    .card.mb-3 {
        position: relative;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .card.mb-3:hover {
        background-color: lightgray;
    }

    .card.mb-3 .card-body {
        position: relative;
        z-index: 2;
    }

    .card.mb-3 .user-link {
        position: relative;
        z-index: 3;
    }
</style>
@section("content")
    <div class="p-5 rounded">
        @auth
            <!-- Sorting Form -->
            <form method="GET" action="{{ route("home.index") }}">
                <label for="sort">Sort by:</label>

                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="latest" {{ request("sort") == "latest" ? "selected" : "" }}>Latest</option>
                    <option value="oldest" {{ request("sort") == "oldest" ? "selected" : "" }}>Oldest</option>
                </select>
            </form>
            <br>
            @foreach ($posts as $item)
                <div class="card mb-3" onclick="location.href='{{ route("home.post.detail", ["id" => $item->id]) }}'"
                    style="cursor: pointer;">
                    <div class="card-body">
                        @if ($item->user)
                            <a href="{{ route("user.show", ["id" => $item->user->id]) }}"
                                class="user-link">{{ $item->user->username }}</a>
                        @else
                            <span>Unknown User</span>
                        @endif

                        @if ($item->title)
                            <h5 class="card-title">{{ $item->title }}</h5>
                        @else
                            <span>Unknown Title</span>
                        @endif

                        @if ($item->description)
                            <p class="card-text text-truncate-2">
                                {{ $item->description }}
                            </p>
                            <br>
                            <small class="text-body-secondary">
                                Posted {{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}
                            </small>
                        @else
                            <span>Unknown Title</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @endauth
        @guest
            <h1>Homepage</h1>
            <h6>You're not logged in</h6>
            <div class="card" style="width: 18rem;">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                </ul>
                <div class="card-body">
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
        @endguest
    </div>
@endsection
