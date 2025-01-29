@extends("layouts.app-master")

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
                <div class="card mb-3">
                    <div class="card-body">
                        @if ($item->user)
                            <a href="{{ route("user.show", ["id" => $item->user->id]) }}">{{ $item->user->username }}</a>
                        @else
                            <span>Unknown User</span>
                        @endif
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                        <small class="text-body-secondary">
                            Posted {{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}
                        </small>
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
