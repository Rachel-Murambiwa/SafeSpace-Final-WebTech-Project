  function toggleComments(id) {
            var x = document.getElementById("comments-" + id);
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

function moveSlide(postId, direction) {
    const container = document.getElementById('carousel-' + postId);
    const images = container.getElementsByClassName('carousel-img');
    let activeIndex = 0;

    // Find current active image
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

// Global object to hold files (acts like a virtual input)
let dt = new DataTransfer();

function handleFiles(input) {
    const container = document.getElementById('preview-container');
    const files = input.files;

    // Loop through newly selected files
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Add to our DataTransfer object
        dt.items.add(file);

        // Create visual elements
        const wrapper = document.createElement('div');
        wrapper.className = 'preview-wrapper';

        const img = document.createElement('img');
        img.className = 'preview-img';
        img.file = file;
        
        // Read the file to show preview
        const reader = new FileReader();
        reader.onload = (function(aImg) { 
            return function(e) { aImg.src = e.target.result; }; 
        })(img);
        reader.readAsDataURL(file);

        // Create "X" button
        const removeBtn = document.createElement('button');
        removeBtn.className = 'btn-remove-img';
        removeBtn.innerHTML = '✕';
        
        // Logic to remove file on click
        removeBtn.onclick = function(e) {
            e.preventDefault(); // Prevent form submit
            
            // Remove the visual thumbnail
            wrapper.remove();
            
            // Remove from DataTransfer object
            // We have to rebuild the list because DataTransfer doesn't have a simple 'remove' method for files
            const newDt = new DataTransfer();
            for (let j = 0; j < dt.items.length; j++) {
                // If the file name doesn't match the one we are deleting, keep it
                if (file.name !== dt.items[j].getAsFile().name) {
                    newDt.items.add(dt.items[j].getAsFile());
                }
            }
            dt = newDt; // Update global list
            
            // Update the actual hidden input
            document.getElementById('file-upload').files = dt.files;
        };

        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
    }

    // Update the actual hidden input with our accumulated list
    input.files = dt.files;
}