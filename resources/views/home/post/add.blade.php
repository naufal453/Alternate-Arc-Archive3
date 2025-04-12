<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<!-- Add Post Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 700px;">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Story</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: calc(100% - 120px);">
                <form action="{{ url('posts') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- Title input -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                            maxlength="45" required />
                    </div>

                    <!-- Genre checklist -->
                    {{-- <div class="mb-3">
                        <label class="form-label">Genres</label>
                        <div class="genre-checklist">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-fantasy" name="genres[]"
                                    value="1">
                                <label class="form-check-label" for="genre-fantasy">Fantasy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-scifi" name="genres[]"
                                    value="2">
                                <label class="form-check-label" for="genre-scifi">Sci-Fi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-romance" name="genres[]"
                                    value="3">
                                <label class="form-check-label" for="genre-romance">Romance</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-mystery" name="genres[]"
                                    value="4">
                                <label class="form-check-label" for="genre-mystery">Mystery</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-horror" name="genres[]"
                                    value="5">
                                <label class="form-check-label" for="genre-horror">Horror</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-thriller" name="genres[]"
                                    value="6">
                                <label class="form-check-label" for="genre-thriller">Thriller</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-adventure" name="genres[]"
                                    value="7">
                                <label class="form-check-label" for="genre-adventure">Adventure</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="genre-other" name="genres[]"
                                    value="8">
                                <label class="form-check-label" for="genre-other">Other</label>
                            </div>
                        </div>
                        <div class="form-text">Check all genres that apply to your post</div>
                    </div> --}}
                    <style>
                        .genre-checklist {
                            background: #f8f9fa;
                            padding: 15px;
                            border-radius: 8px;
                            border: 1px solid #dee2e6;
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                            gap: 10px;
                        }

                        .form-check-input:checked {
                            background-color: #0d6efd;
                            border-color: #0d6efd;
                        }
                    </style>

                    <!-- Quill Editor -->
                    <div id="quill-editor" style="height: 350px;"></div>
                    <input type="hidden" id="description" name="description">
                    <br>
                    <!-- Image upload with preview -->
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" id="image" name="image" accept="image/*"
                            required onchange="previewImage(event)" />
                        <label class="input-group-text" for="image">Add Cover</label>
                    </div>
                    <div class="mb-3 text-center">
                        <img id="imagePreview" src="#" alt="Image Preview"
                            style="display: none; max-width: 100%; height: auto; border-radius: 10px;" />
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<!-- DOMPurify -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.4.0/dist/purify.min.js"></script>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Write your content here...',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'image'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['clean']
                ]
            }
        });

        var form = document.querySelector('form');
        form.addEventListener('submit', function() {
            var descriptionInput = document.querySelector('#description');
            var quillContent = quill.root.innerHTML;
            var sanitizedContent = DOMPurify.sanitize(quillContent);
            descriptionInput.value = sanitizedContent;
        });
    });
</script>
