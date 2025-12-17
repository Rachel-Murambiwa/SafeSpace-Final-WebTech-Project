function toggleComments(id) {
    var x = document.getElementById("comments-" + id);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
        }

// 2. Carousel Logic
function moveSlide(postId, direction) {
    const container = document.getElementById('carousel-' + postId);
    if (!container) return;
    const images = container.getElementsByClassName('carousel-img');
    let activeIndex = 0;

    for (let i = 0; i < images.length; i++) {
        if (images[i].classList.contains('active')) {
            activeIndex = i;
            images[i].classList.remove('active');
            break;
        }
    }

    let newIndex = activeIndex + direction;
    if (newIndex >= images.length) newIndex = 0;
    if (newIndex < 0) newIndex = images.length - 1;

    images[newIndex].classList.add('active');
}

// 3. File Upload Preview
let dt = new DataTransfer();
function handleFiles(input) {
    const container = document.getElementById('preview-container');
    const files = input.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        dt.items.add(file);

        const wrapper = document.createElement('div');
        wrapper.className = 'preview-wrapper';

        const img = document.createElement('img');
        img.className = 'preview-img';
        img.file = file;

        const reader = new FileReader();
        reader.onload = (function(aImg) { 
            return function(e) { aImg.src = e.target.result; }; 
        })(img);
        reader.readAsDataURL(file);

        const removeBtn = document.createElement('button');
        removeBtn.className = 'btn-remove-img';
        removeBtn.innerHTML = '✕';
        
        removeBtn.onclick = function(e) {
            e.preventDefault();
            wrapper.remove();
            const newDt = new DataTransfer();
            for (let j = 0; j < dt.items.length; j++) {
                if (file.name !== dt.items[j].getAsFile().name) {
                    newDt.items.add(dt.items[j].getAsFile());
                }
            }
            dt = newDt;
            document.getElementById('file-upload').files = dt.files;
        };

        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
    }
    input.files = dt.files;
}

// 4. FLAG MODAL LOGIC (This is the critical part)
function openFlagModal(commentId) {
    const modal = document.getElementById('flagModal');
    const confirmBtn = document.getElementById('confirmFlagBtn');

    // Dynamically set the delete link with the correct ID
    confirmBtn.href = "../actions/flag_comment.php?id=" + commentId;

    // Show the modal
    modal.style.display = 'flex';
}

function closeFlagModal() {
    document.getElementById('flagModal').style.display = 'none';
}

// Close modal if user clicks outside the white content box
window.onclick = function(event) {
    const modal = document.getElementById('flagModal');
    if (event.target === modal) {
        closeFlagModal();
    }
}