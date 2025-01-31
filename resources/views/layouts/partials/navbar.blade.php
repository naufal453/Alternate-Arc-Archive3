<style>
    .navbar-fixed-height {
        height: 70px;
        /* Set your desired height */
    }

    .navbar-brand {
        margin-right: 20px;
    }

    #search {
        flex-grow: 1;
    }

    .btn-toolbar {
        margin-left: 20px;
    }

    .btn {
        margin-right: 20px;
    }

    #search {
        padding-top: 10px
    }
</style>
<nav class="navbar fixed-top bg-dark navbar-fixed-height">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a href="/" class="navbar-brand text-white">Tech Forum</a>
        <form id="search" class="d-flex align-items-center" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        </form>
        @auth
            <div class="btn-toolbar d-flex align-items-center" role="toolbar">
                <!-- Add Post Button -->
                <button class="btn btn-outline-light btn-sm me-2 d-flex align-items-center" type="button"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Add Post
                </button>

                <div class="btn-group" role="group">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- User Avatar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-person-square" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
                        </svg>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route("user.show", ["id" => auth()->user()->id]) }}">My
                                Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="{{ route("logout.perform") }}" class="dropdown-item">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth

        @guest
            <div class="text-end">
                <a href="{{ route("login.perform") }}" class="btn btn-outline-light me-2">Login</a>
                <a href="{{ route("register.perform") }}" class="btn btn-warning">Sign-up</a>
            </div>
        @endguest
    </div>
</nav>
<!-- Add Post Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Post</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url("posts") }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- Title input -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                            required />
                    </div>

                    <!-- Content textarea -->
                    <div class="mb-3">
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Write your content here"
                            required></textarea>
                    </div>

                    <!-- Image upload -->
                    <div class="mb-3">
                        <input class="form-control" type="file" id="image" name="image" />
                    </div>

                    <!-- Submit button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
