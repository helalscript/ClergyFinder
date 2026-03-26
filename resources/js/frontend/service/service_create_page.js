    $(document).ready(function() {
        $('.dropify').dropify();
    })



    $(document).ready(function() {
        $('#car_brand').select2({
            tags: true, // Allows users to enter new values
            placeholder: "Select or type a new car brand",
            allowClear: true,
            tokenSeparators: [','], // Users can enter multiple values separated by commas
        });
        // $('#subcategory, #kilometers_driven, #fuel_type, #car_type').select2({
            //     tags: false, // Allows users to enter new values
            //     placeholder: "Select or type a new car brand",
            //     allowClear: true,
            //     tokenSeparators: [','], // Users can enter multiple values separated by commas
            // });
        });
    


    //JavaScript to Enable & Filter Subcategories

    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const subcategorySelect = document.getElementById('subcategory');

        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.options[this.selectedIndex];
            const subcategories = JSON.parse(selectedCategory.getAttribute('data-subcategories'));

            // Clear and disable subcategory dropdown if no category selected
            subcategorySelect.innerHTML =
                '<option value="" selected disabled>Select Subcategory</option>';
            subcategorySelect.disabled = true;

            if (subcategories.length > 0) {
                subcategories.forEach(subcategory => {
                    let option = new Option(subcategory.name, subcategory.id);
                    subcategorySelect.appendChild(option);
                });
                subcategorySelect.disabled = false; // Enable subcategory dropdown
            }
        });
    });



    // This JavaScript code is used in the service create page to enable and filter
    // subcategories based on the category selected. It also handles the file upload
    // for the gallery images.
    $(document).ready(function() {
        let uploadedImages = [];
        const dropzone = $('#gallery-dropzone');
        const previewContainer = $('#preview-container');
        const galleryImagesInput = $('#gallery-images')[0];
        const maxFiles = 20; // Maximum allowed file uploads

        dropzone.on('dragover', function(event) {
            event.preventDefault();
            dropzone.addClass('border-primary');
        });

        dropzone.on('dragleave', function() {
            dropzone.removeClass('border-primary');
        });

        dropzone.on('drop', function(event) {
            event.preventDefault();
            dropzone.removeClass('border-primary');
            let files = event.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        dropzone.on('click', function() {
            let fileInput = $('<input>', {
                type: 'file',
                accept: 'image/jpeg,image/png,image/jpg',
                multiple: true
            }).on('change', function(event) {
                handleFiles(event.target.files);
            });
            fileInput.trigger('click');
        });

        function handleFiles(files) {
            // console.log(files.length)
            if (uploadedImages.length >= maxFiles || files.length >= maxFiles) {
                flasher.warning(`You can only upload up to ${maxFiles} images.`);
                // alert(`You can only upload up to ${maxFiles} images.`);
                return;
            }
                $.each(files, function(index, file) {
                const maxSize = 2 * 1024 * 1024; // 2MB limit

                if (file.size > maxSize) {
                    // alert(`File "${file.name}" size exceeds the maximum allowed size (2MB).`);
                    // console.log(`File "${file.name}" exceeds 2MB and will not be uploaded.`);
                    flasher.error(`"${file.name}" exceeds 2MB and will not be uploaded.`);
                return;
                }
                // Check if file is already uploaded
                if (uploadedImages.some(img => img.name === file.name && img.size === file.size)) {
                    flasher.warning(`"${file.name}" is already added.`);
                return;
                }



                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                reader.onload = function(event) {
                    let imgUrl = event.target.result;
                uploadedImages.push(file);
                updateGalleryImagesInput();

                // Create preview
                let previewCard = $('<div>', {
                    class: 'position-relative rounded overflow-hidden shadow-sm m-2'
                        });

                    let imgElement = $('<img>', {
                        src: imgUrl,
                        class: 'img-thumbnail rounded',
                        css: {
                            height: '200px',
                        width: '200px'
                            }
                        });

                        let deleteButton = $('<button>', {
                            html: '&times;',
                            class: 'position-absolute top-0 end-0 btn-sm btn-danger rounded-circle'
                        }).on('click', function() {
                                uploadedImages = uploadedImages.filter(item => item !== file);
                            previewCard.remove();
                            updateGalleryImagesInput();
                        });

                            previewCard.append(imgElement, deleteButton);
                            previewContainer.append(previewCard);
                    };
                            reader.readAsDataURL(file);
                }
            });
        }

                            function updateGalleryImagesInput() {
                                let dataTransfer = new DataTransfer();
                            $.each(uploadedImages, function(index, file) {
                                dataTransfer.items.add(file);
            });
                            galleryImagesInput.files = dataTransfer.files;
            // console.log(galleryImagesInput.files);
        }
    });
                      