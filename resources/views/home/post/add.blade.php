<!-- Add Post Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Story</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('posts') }}" method="post" enctype="multipart/form-data">
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
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" id="image" name="image" required />
                        <label class="input-group-text" for="inputGroupFile02">Add Cover</label>
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
