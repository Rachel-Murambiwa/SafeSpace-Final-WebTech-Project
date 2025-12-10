
function openDeleteModal(postId) {
const modal = document.getElementById('deleteModal');
const confirmBtn = document.getElementById('confirmDeleteBtn');
confirmBtn.href = "../actions/delete_post.php?id=" + postId;
modal.style.display = 'flex';
}

function closeDeleteModal() {
document.getElementById('deleteModal').style.display = 'none';
}

window.onclick = function(event) {
const modal = document.getElementById('deleteModal');
if (event.target == modal) {
    modal.style.display = "none";
}
}

function moveSlide(postId, direction) {
        const container = document.getElementById('carousel-' + postId);
        const images = container.getElementsByClassName('carousel-img');
        let activeIndex = 0;

        // Find current active
        for (let i = 0; i < images.length; i++) {
            if (images[i].classList.contains('active')) {
                activeIndex = i;
                images[i].classList.remove('active');
                break;
            }
        }

        // Calculate new index
        let newIndex = activeIndex + direction;
        if (newIndex >= images.length) newIndex = 0;
        if (newIndex < 0) newIndex = images.length - 1;

        // Show new image
        images[newIndex].classList.add('active');
    }

    // 2. MODAL SCRIPT
    function openDeleteModal(postId) {
        const modal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        confirmBtn.href = "../actions/delete_post.php?id=" + postId;
        modal.style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }