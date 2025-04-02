document.addEventListener('DOMContentLoaded', function () {
    const likeButton = document.getElementById('like-button');
    const likeCount = document.getElementById('like-count');
    const likeText = document.getElementById('like-text');

    if (likeButton) {
        likeButton.addEventListener('click', function () {
            const postId = likeButton.getAttribute('data-post-id');
            const liked = likeButton.getAttribute('data-liked') === 'true';

            fetch(liked ? '/likes' : '/likes', {
                method: liked ? 'DELETE' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
                body: JSON.stringify({
                    post_id: postId
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        likeButton.setAttribute('data-liked', liked ? 'false' : 'true');
                        likeButton.classList.toggle('btn-danger', !liked);
                        likeButton.classList.toggle('btn-primary', liked);
                        likeText.textContent = liked ? 'Like' : 'Unlike';

                        // Update like count
                        likeCount.textContent =
                            `${data.likes_count} ${data.likes_count === 1 ? 'like' : 'likes'}`;
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
});