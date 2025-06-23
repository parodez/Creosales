document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.querySelector('.contact-add-btn');
    const addContactModal = document.getElementById('addContactModal');
    const addContactForm = document.getElementById('addContactForm');
    const moreDetailsModal = document.getElementById('moreDetailsModal');

    if (addBtn) {
        addBtn.addEventListener('click', function () {
            addContactModal.style.display = 'block';
        });
    }

    window.closeAddContactModal = function () {
        addContactModal.style.display = 'none';
        addContactForm.reset();
    };

    // Close modal when clicking outside
    window.addEventListener('click', function (event) {
        if (event.target === addContactModal) {
            closeAddContactModal();
        }
    });

    // Add input restriction for contact number in add contact form
    document.getElementById('addNumber').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9\-]/g, '');
    });

    // Submit handler for add contact
    addContactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(addContactForm);
        formData.append('action', 'add_contactperson');
        formData.append('client_id', clientId);

        fetch('../Backend/fetch_data_creosales.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Success', 'Contact added successfully!', 'success').then(() => {
                    closeAddContactModal();
                    // Optionally, refresh the contact list
                    document.getElementById('moreDetailsBtn').click();
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to add contact.', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Unexpected error occurred.', 'error');
        });
    });

    function attachEditHandler(btn) {
        btn.addEventListener('click', function editHandler() {
            const tr = btn.closest('tr');
            if (!tr) return;

            const contactpersonId = tr.dataset.contactpersonId;
            const position = tr.dataset.position;
            const name = tr.dataset.name;
            const email = tr.dataset.email;
            const number = tr.dataset.number;
            const originalHTML = tr.innerHTML;

            tr.innerHTML = `
                <td><input type="text" class="form-control form-control-sm" value="${position}"></td>
                <td><input type="text" class="form-control form-control-sm" value="${name}"></td>
                <td><input type="email" class="form-control form-control-sm" value="${email}"></td>
                <td><input type="text" class="form-control form-control-sm contact-number-input" value="${number}"></td>
                <td>
                    <button class="btn btn-sm btn-success contact-save-btn">Save</button>
                    <button class="btn btn-sm btn-secondary contact-cancel-btn">Cancel</button>
                </td>
            `;

            // Preserve data attributes
            Object.assign(tr.dataset, {
                contactpersonId,
                position,
                name,
                email,
                number
            });

            tr.style.backgroundColor = '#4100BF';
            tr.style.color = 'white';

            // Number input restriction
            tr.querySelector('.contact-number-input').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9\-]/g, '');
            });

            // Cancel handler
            tr.querySelector('.contact-cancel-btn').addEventListener('click', function() {
                tr.innerHTML = originalHTML;
                tr.style.backgroundColor = '#4100BF';
                tr.style.color = 'white';
                const newEditBtn = tr.querySelector('.contact-edit-btn');
                if (newEditBtn) attachEditHandler(newEditBtn);
            });

            // Save handler
            tr.querySelector('.contact-save-btn').addEventListener('click', function() {
                const inputs = tr.querySelectorAll('input');
                const formData = new FormData();
                formData.append('action', 'update_contactperson');
                formData.append('contactperson_id', contactpersonId);
                formData.append('contactperson_position', inputs[0].value.trim());
                formData.append('contactperson_name', inputs[1].value.trim());
                formData.append('contactperson_email', inputs[2].value.trim());
                formData.append('contactperson_number', inputs[3].value.trim());

                fetch('../Backend/fetch_data_creosales.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Success', 'Contact updated successfully!', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed to update contact.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                });
            });
        });
    }

    // Initial attachment of edit handlers
    document.querySelectorAll('.contact-edit-btn').forEach(btn => {
        attachEditHandler(btn);
    });

    document.getElementById('moreDetailsBtn').addEventListener('click', function () {

        fetch(`../backend/data/contactDetails.php?action=fetch_contact_details&client_id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const tbody = document.querySelector('#moreDetailsModal table tbody');
                    tbody.innerHTML = '';

                    if (data.data.length > 0) {
                        data.data.forEach(detail => {
                            const row = document.createElement('tr');
                            row.dataset.contactpersonId = detail.contactperson_id;
                            row.dataset.position = detail.contactperson_position || '';
                            row.dataset.name = detail.contactperson_name || '';
                            row.dataset.email = detail.contactperson_email || '';
                            row.dataset.number = detail.contactperson_number || '';
                            
                            row.innerHTML = `
                                <td>${detail.contactperson_position || 'N/A'}</td>
                                <td>${detail.contactperson_name || 'N/A'}</td>
                                <td>${detail.contactperson_email || 'N/A'}</td>
                                <td>${detail.contactperson_number || 'N/A'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary contact-edit-btn">Edit</button>
                                </td>
                            `;
                            row.style.backgroundColor = '#4100BF';
                            row.style.color = 'white';
                            tbody.appendChild(row);
                        });

                        // Add edit button click handlers
                        document.querySelectorAll('.contact-edit-btn').forEach(btn => {
                            attachEditHandler(btn);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No data found.</td></tr>';
                    }

                    document.getElementById('moreDetailsModal').style.display = 'block';
                }
            })
            .catch(error => console.log('Error:', error));
    });

    window.addEventListener('click', function (event) {
        const modal = document.getElementById('moreDetailsModal');
        if (event.target === modal) {
            closeMoreDetailsModal();
        }
    });
});

function closeMoreDetailsModal() {
    document.getElementById('moreDetailsModal').style.display = 'none';
}

// Define imagesData and other variables at the top level
        let currentImageIndex = 0;
        let autoPlayInterval;

        // Function to update image display
        function updateImageDisplay() {
            const imgElement = document.getElementById('currentImage');
            const nameElement = document.getElementById('imageName');
            const dateElement = document.getElementById('imageDate');

            if (imgElement && nameElement && dateElement) {
                if (imagesData && imagesData.length > 0) {
                    const currentImage = imagesData[currentImageIndex];

                    if (currentImage.images_path) {
                        imgElement.src = currentImage.images_path;
                        imgElement.style.display = 'block';
                        if (imgElement.nextElementSibling) {
                            imgElement.nextElementSibling.style.display = 'none';
                        }
                    } else {
                        imgElement.src = '';
                        imgElement.alt = 'No Image Found';
                        imgElement.style.display = 'none';
                        if (imgElement.nextElementSibling) {
                            imgElement.nextElementSibling.style.display = 'flex';
                        }
                    }

                    nameElement.textContent = currentImage.images_name || 'No name available';
                    dateElement.textContent = formatDate(currentImage.images_date) || 'No date available';
                } else {
                    imgElement.src = '';
                    imgElement.alt = 'No Image Found';
                    imgElement.style.display = 'none';
                    if (imgElement.nextElementSibling) {
                        imgElement.nextElementSibling.style.display = 'flex';
                    }
                    nameElement.textContent = 'No image found';
                    dateElement.textContent = '';
                }
            } else {
                console.error('Image element or details elements not found');
            }
        }

        // Function to update evaluation summary
        function updateEvaluationSummary() {
            if (evalSummary) {
                // Update rating - use the select element
                const ratingSelect = document.getElementById('ratingSelect');
                if (ratingSelect) {
                    // Set the selected option
                    for (let i = 0; i < ratingSelect.options.length; i++) {
                        if (ratingSelect.options[i].value === evalSummary.evaluation_rating) {
                            ratingSelect.selectedIndex = i;
                            break;
                        }
                    }
                }

                // Update result - use the radio buttons
                const resultOptions = document.querySelectorAll('.result-option input[type="radio"]');
                resultOptions.forEach(radio => {
                    if (radio.value === evalSummary.evaluation_result) {
                        radio.checked = true;
                        // Trigger click on parent to update visual state
                        radio.closest('.result-option').click();
                    }
                });

                // Update date
                const dateElement = document.getElementById('evalDate');
                if (dateElement) {
                    dateElement.textContent = `Date: ${formatDate(evalSummary.evaluation_date)}`;
                }
            }
        }

        // Helper function to format dates
        function formatDate(dateString) {
            if (!dateString) return 'Not available';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Navigation functions
        function previousImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
                updateImageDisplay();
                updateModalImage();
            }
        }

        function nextImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex + 1) % imagesData.length;
                updateImageDisplay();
                updateModalImage();
            }
        }

        // Add event listeners for navigation buttons globally
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.carousel-button.left').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (btn.closest('#imageModal')) {
                        previousModalImage();
                    } else {
                        previousImage();
                    }
                });
            });
            document.querySelectorAll('.carousel-button.right').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (btn.closest('#imageModal')) {
                        nextModalImage();
                    } else {
                        nextImage();
                    }
                });
            });
        });

        function updateModalImage() {
            const modalImg = document.getElementById("modalImage");
            const captionText = document.getElementById("modalCaption");

            if (modalImg && captionText) {
                if (imagesData && imagesData.length > 0) {
                    const currentImage = imagesData[currentImageIndex];
                    const imageName = currentImage.images_name || 'No name available';
                    const imageDate = formatDate(currentImage.images_date) || 'No date available';

                    modalImg.src = currentImage.images_path || ''; // No image
                    modalImg.alt = 'No Image Found';
                    captionText.innerHTML = `<b>${imageName}</b><br>${imageDate}`;
                } else {
                    modalImg.src = ''; // No image
                    modalImg.alt = 'No Image Found';
                    captionText.innerHTML = '<b>No image found</b>';
                }

                // Add mini images carousel
                const miniImagesContainer = document.createElement('div');
                miniImagesContainer.classList.add('mini-images-carousel');
                imagesData.forEach((image, index) => {
                    const miniImage = document.createElement('img');
                    miniImage.src = image.images_path || ''; // No image
                    miniImage.alt = 'No Image Found';
                    miniImage.classList.add('mini-image');
                    miniImage.addEventListener('click', () => {
                        currentImageIndex = index;
                        updateImageDisplay();
                        updateModalImage();
                    });
                    miniImagesContainer.appendChild(miniImage);
                });
                captionText.appendChild(miniImagesContainer);
            } else {
                console.error('Modal image or caption elements not found');
            }
        }

        // New modal navigation functions to prevent updating the carousel
        function previousModalImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
                updateModalImage();
            }
        }

        function nextModalImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex + 1) % imagesData.length;
                updateModalImage();
            }
        }

        // Tab switching functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab
                tab.classList.add('active');

                // Show corresponding content
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        //Add Error handling to prevent null references
        function safeUpdateElement(id, updateFn) {
            const element = document.getElementById(id);
            if (element) {
                updateFn(element);
            } else {
                console.warn(`Element with ID ${id} not found`);
            }
        }

        //notes
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".notes-textarea").forEach(textarea => {

                const initialTextareaValues = {};
                document.querySelectorAll(".notes-textarea").forEach(textarea => {
                    initialTextareaValues[textarea.name] = textarea.value;

                    if (textarea.value.trim() === "empty notes") {
                        textarea.classList.add("gray-out");
                    }

                    textarea.addEventListener("focus", function() {
                        if (this.value.trim() === "empty notes") {
                            this.value = "";
                            this.classList.remove("gray-out");
                        }
                    });

                    textarea.addEventListener("blur", function() {
                        if (this.value.trim() === "") {
                            this.value = "empty notes";
                            this.classList.add("gray-out");
                        }
                    });
                });
            });
        });

        //show pop-up container
        document.addEventListener('DOMContentLoaded', function() {
            const evaluationInfoBox = document.getElementById('evaluation-info-box');

            // Get the PHP data

            // Function to update image display
            function updateImageDisplay() {
                const imgElement = document.getElementById('currentImage');
                const nameElement = document.getElementById('imageName');
                const dateElement = document.getElementById('imageDate');

                if (imgElement && nameElement && dateElement) {
                    if (imagesData && imagesData.length > 0) {
                        const currentImage = imagesData[currentImageIndex];

                        // Update image source - check if images_path exists
                        if (currentImage.images_path) {
                            imgElement.src = currentImage.images_path;
                        } else {
                            imgElement.src = ''; // No image
                            imgElement.alt = 'No Image Found';
                        }

                        // Update image details
                        nameElement.textContent = currentImage.images_name || 'No name available';
                        dateElement.textContent = formatDate(currentImage.images_date) || 'No date available';
                    } else {
                        // Display "No image found" when no data is fetched
                        imgElement.src = ''; // No image
                        imgElement.alt = 'No Image Found';
                        nameElement.textContent = 'No image found';
                        dateElement.textContent = '';
                    }
                } else {
                    console.error('Image element or details elements not found');
                }
            }

            // Function to update evaluation summary
            function updateEvaluationSummary() {
                if (evalSummary) {
                    // Update rating - use the select element
                    const ratingSelect = document.getElementById('ratingSelect');
                    if (ratingSelect) {
                        // Set the selected option
                        for (let i = 0; i < ratingSelect.options.length; i++) {
                            if (ratingSelect.options[i].value === evalSummary.evaluation_rating) {
                                ratingSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }

                    // Update result - use the radio buttons
                    const resultOptions = document.querySelectorAll('.result-option input[type="radio"]');
                    resultOptions.forEach(radio => {
                        if (radio.value === evalSummary.evaluation_result) {
                            radio.checked = true;
                            // Trigger click on parent to update visual state
                            radio.closest('.result-option').click();
                        }
                    });

                    // Update date
                    const dateElement = document.getElementById('evalDate');
                    if (dateElement) {
                        dateElement.textContent = `Date: ${formatDate(evalSummary.evaluation_date)}`;
                    }
                }
            }

            // Helper function to format dates
            function formatDate(dateString) {
                if (!dateString) return 'Not available';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            // Navigation functions
            function previousImage() {
                if (imagesData && imagesData.length > 0) {
                    currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
                    updateImageDisplay();
                }
            }

            function nextImage() {
                if (imagesData && imagesData.length > 0) {
                    currentImageIndex = (currentImageIndex + 1) % imagesData.length;
                    updateImageDisplay();
                }
            }

            // Set up auto-play functionality
            function startAutoPlay() {
                stopAutoPlay(); // Clear any existing interval
                autoPlayInterval = setInterval(nextImage, 4000); // Change image every 3 seconds
            }

            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                }
            }

            // Show/hide evaluation info box on tab click
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    if (tab.getAttribute('data-tab') === 'evaluation') {
                        evaluationInfoBox.style.display = 'block';
                        // Initialize displays when tab is shown
                        updateImageDisplay();
                        updateEvaluationSummary();
                        startAutoPlay();
                    } else {
                        evaluationInfoBox.style.display = 'none';
                        stopAutoPlay();
                    }
                });
            });

            // Initial setup if evaluation tab is active
            if (document.querySelector('.tab[data-tab="evaluation"]').classList.contains('active')) {
                evaluationInfoBox.style.display = 'block';
                updateImageDisplay();
                updateEvaluationSummary();
                startAutoPlay();
            }
        });

        // merge save buttons into one
        function saveAllChanges() {
            Swal.fire({
                title: 'Save Changes',
                text: 'Are you sure you want to save all changes?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save changes!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#e77373',
                cancelButtonColor: '#6c757d'

            }).then((result) => {
                if (result.isConfirmed) {
                    // Get form data from both forms
                    const evaluationFormData = new FormData(document.getElementById('evaluationForm'));
                    const notesFormData = new FormData(document.querySelector('#evaluation form'));

                    // Combine both forms' data
                    const combinedFormData = new FormData();

                    // Add evaluation form data
                    for (let [key, value] of evaluationFormData.entries()) {
                        combinedFormData.append(key, value);
                    }

                    // Add notes form data
                    for (let [key, value] of notesFormData.entries()) {
                        combinedFormData.append(key, value);
                    }

                    // Send the combined data
                    fetch('../Backend/update_evaluation.php', {
                        method: 'POST',
                        body: combinedFormData
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(`Server responded with status: ${response.status}`);
                        }

                        // Check the content type to handle different responses
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // If not JSON, get text and try to parse it or handle as error
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    throw new Error('The server returned an invalid response: ' + text.substring(0, 100));
                                }
                            });
                        }
                    }).then(data => {
                        if (data.status === "success") {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#e77373'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error saving changes: ' + data.message,
                                icon: 'error',
                                confirmButtonColor: '#e77373'
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'An unexpected error occurred while saving changes. ' + error.message,
                            icon: 'error',
                            confirmButtonColor: '#e77373'
                        });
                    });
                }
            });
        }

        //animation on sliding selection passed,failed and conditional
        document.addEventListener('DOMContentLoaded', function() {

            const resultSlider = document.querySelector('.result-options');
            const resultOptions = document.querySelectorAll('.result-option');
            let currentResultIndex = 0;

            const evaluationSaveBtn = document.querySelector('#evaluationForm .save-btn');
            const notesSaveBtn = document.querySelector('#evaluation .save-btn');

            // Initialize selected result
            resultOptions.forEach((option, index) => {
                if (option.querySelector('input[type="radio"]').checked) {
                    currentResultIndex = index;
                    updateResultSlider();
                }
            });

            // Handle result option clicks
            resultOptions.forEach((option, index) => {
                option.addEventListener('click', () => {
                    currentResultIndex = index;
                    option.querySelector('input[type="radio"]').checked = true;
                    updateResultSlider();
                    updateSelectedStyles();
                });
            });

            // Handle slider navigation
            document.querySelector('.prev-result').addEventListener('click', () => {
                if (currentResultIndex > 0) {
                    currentResultIndex--;
                    updateResultSlider();
                    resultOptions[currentResultIndex].click();
                }
            });

            document.querySelector('.next-result').addEventListener('click', () => {
                if (currentResultIndex < resultOptions.length - 1) {
                    currentResultIndex++;
                    updateResultSlider();
                    resultOptions[currentResultIndex].click();
                }
            });

            // Update slider position
            function updateResultSlider() {
                const translateX = -(currentResultIndex * (100 / 3));
                resultSlider.style.transform = `translateX(${translateX}%)`;
                updateSliderNavigation();
            }

            // Update navigation button states
            function updateSliderNavigation() {
                document.querySelector('.prev-result').style.visibility =
                    currentResultIndex === 0 ? 'hidden' : 'visible';
                document.querySelector('.next-result').style.visibility =
                    currentResultIndex === resultOptions.length - 1 ? 'hidden' : 'visible';
            }

            // Update selected styles
            function updateSelectedStyles() {
                resultOptions.forEach(option => option.classList.remove('selected'));
                resultOptions[currentResultIndex].classList.add('selected');
            }

            if (evaluationSaveBtn) {
                evaluationSaveBtn.textContent = 'Save';
                evaluationSaveBtn.removeEventListener('click', null);
                evaluationSaveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }

            if (notesSaveBtn) {
                notesSaveBtn.textContent = 'Save';
                notesSaveBtn.removeEventListener('click', null);
                notesSaveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }

            // Override form submissions
            const evaluationForm = document.getElementById('evaluationForm');
            if (evaluationForm) {
                evaluationForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }

            const notesForm = document.querySelector('#evaluation form');
            if (notesForm) {
                notesForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }


            // Enable touch swiping for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            resultSlider.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });

            resultSlider.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0 && currentResultIndex < resultOptions.length - 1) {
                        // Swipe left
                        document.querySelector('.next-result').click();
                    } else if (diff < 0 && currentResultIndex > 0) {
                        // Swipe right
                        document.querySelector('.prev-result').click();
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const initialTextareaValues = {};
            const initialSelectValues = {};
            const initialRadioValues = {};
            let hasUnsavedChanges = false;

            // Store initial values of textareas
            document.querySelectorAll(".notes-textarea").forEach(textarea => {
                initialTextareaValues[textarea.name] = textarea.value;
                
                // Add change listener for textareas
                textarea.addEventListener('input', function() {
                    checkForChanges();
                });
            });

            // Store initial values of select elements
            document.querySelectorAll("select").forEach(select => {
                initialSelectValues[select.name] = select.value;
                
                // Add change listener for selects
                select.addEventListener('change', function() {
                    checkForChanges();
                });
            });

            // Store initial values of radio buttons
            document.querySelectorAll("input[type='radio']").forEach(radio => {
                if (radio.checked) {
                    initialRadioValues[radio.name] = radio.value;
                }
                
                // Add change listener for radios
                radio.addEventListener('change', function() {
                    if (radio.checked) {
                        checkForChanges();
                    }
                });
            });

            // Specifically handle the rating select element
            const ratingSelect = document.getElementById('ratingSelect');
            if (ratingSelect) {
                initialSelectValues[ratingSelect.name] = ratingSelect.value;
                ratingSelect.addEventListener('change', function() {
                    checkForChanges();
                });
            }

            // Handle result option clicks
            document.querySelectorAll('.result-option').forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        checkForChanges();
                    }
                });
            });

            // Function to check for unsaved changes
            function checkForChanges() {
                hasUnsavedChanges = false;

            // Check textareas
            document.querySelectorAll(".notes-textarea").forEach(textarea => {
                if (textarea.value !== initialTextareaValues[textarea.name]) {
                    hasUnsavedChanges = true;
                }
            });

            // Check select elements
            document.querySelectorAll("select").forEach(select => {
                if (select.value !== initialSelectValues[select.name]) {
                    hasUnsavedChanges = true;
                }
            });

            // Check radio buttons
            document.querySelectorAll("input[type='radio']").forEach(radio => {
                if (radio.checked && initialRadioValues[radio.name] && 
                    radio.value !== initialRadioValues[radio.name]) {
                    hasUnsavedChanges = true;
                }
            });
        }

        // Add event listener for export button
        const exportBtn = document.querySelector('.export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                // Check for unsaved changes before proceeding
                checkForChanges();
                
                if (hasUnsavedChanges) {
                    Swal.fire({
                        title: 'Unsaved Changes',
                        text: 'Please save your changes before exporting the edited data to Excel.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#90EE90'
                    });
                } else {
                    Swal.fire({
                        title: 'Export Data',
                        text: 'Do you want to export this client\'s data to Excel?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, export it!',
                        cancelButtonText: 'No, cancel',
                        confirmButtonColor: '#90EE90',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Get client_id from URL parameter
                            const urlParams = new URLSearchParams(window.location.search);
                            const clientId = urlParams.get('id');

                            // Redirect to export handler
                            window.location.href = `../Backend/ExportSystemBackend/export_handler.php?client_id=${clientId}`;
                        }
                    });
                }
            });
        }

    // Add event listener for save button to reset the initial values after saving
    const saveButtons = document.querySelectorAll('.save-btn');
    if (saveButtons.length > 0) {
        saveButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // This is handled in the saveAllChanges function's success callback
            });
        });
    }
});

// Modify the saveAllChanges function to update initial values after successful save
function saveAllChanges() {
    Swal.fire({
        title: 'Save Changes',
        text: 'Are you sure you want to save all changes?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, save changes!',
        cancelButtonText: 'No, cancel',
        confirmButtonColor: '#e77373',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get form data from both forms
            const evaluationFormData = new FormData(document.getElementById('evaluationForm'));
            const notesFormData = new FormData(document.querySelector('#evaluation form'));

            // Combine both forms' data
            const combinedFormData = new FormData();

            // Add evaluation form data
            for (let [key, value] of evaluationFormData.entries()) {
                combinedFormData.append(key, value);
            }

            // Add notes form data
            for (let [key, value] of notesFormData.entries()) {
                combinedFormData.append(key, value);
            }

            // Send the combined data
            fetch('../Backend/update_evaluation.php', {
                method: 'POST',
                body: combinedFormData
            }).then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }

                // Check the content type to handle different responses
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, get text and try to parse it or handle as error
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('The server returned an invalid response: ' + text.substring(0, 100));
                        }
                    });
                }
            }).then(data => {
                if (data.status === "success") {
                    // Update initial values to reflect saved state
                    const initialTextareaValues = {};
                    const initialSelectValues = {};
                    const initialRadioValues = {};

                    // Update textareas
                    document.querySelectorAll(".notes-textarea").forEach(textarea => {
                        initialTextareaValues[textarea.name] = textarea.value;
                    });

                    // Update selects
                    document.querySelectorAll("select").forEach(select => {
                        initialSelectValues[select.name] = select.value;
                    });

                    // Update radios
                    document.querySelectorAll("input[type='radio']").forEach(radio => {
                        if (radio.checked) {
                            initialRadioValues[radio.name] = radio.value;
                        }
                    });

                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#e77373'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error saving changes: ' + data.message,
                        icon: 'error',
                        confirmButtonColor: '#e77373'
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected error occurred while saving changes. ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#e77373'
                });
            });
        }
    });
}

function openImageModal() {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    const captionText = document.getElementById("modalCaption");
    const currentImage = document.getElementById("currentImage");
    const imageName = document.getElementById("imageName").textContent;
    const imageDate = document.getElementById("imageDate").textContent;
    const rating = document.getElementById("ratingSelect").value;
    const result = document.querySelector('.result-option input[type="radio"]:checked').value;
    const evalDate = document.getElementById("evalDate").textContent;

    modal.style.display = "block";
    modalImg.src = currentImage.src;
    modalImg.alt = currentImage.alt;
    captionText.innerHTML = `<b>${imageName}</b><br>${imageDate}`;
    document.getElementById("modalRating").textContent = rating || 'No rating found';
    document.getElementById("modalResult").textContent = result || 'No result found';
    document.getElementById("modalEvalDate").textContent = evalDate;

    // Add mini images carousel
    const miniImagesContainer = document.createElement('div');
    miniImagesContainer.classList.add('mini-images-carousel');
    imagesData.forEach((image, index) => {
        const miniImage = document.createElement('img');
        miniImage.src = image.images_path || ''; // No image
        miniImage.alt = 'No Image Found';
        miniImage.classList.add('mini-image');
        miniImage.addEventListener('click', () => {
            currentImageIndex = index;
            updateImageDisplay();
            updateModalImage();
        });
        miniImagesContainer.appendChild(miniImage);
    });
    captionText.appendChild(miniImagesContainer);
}

function closeImageModal() {
    const modal = document.getElementById("imageModal");
    modal.style.display = "none";
    const captionText = document.getElementById("modalCaption");
    while (captionText.firstChild) {
        captionText.removeChild(captionText.firstChild);
    }
}

// Close modal when clicking outside of it
window.addEventListener('click', function(event) {
    const modal = document.getElementById("imageModal");
    if (event.target === modal) {
        closeImageModal();
    }
});

function openCriteriaModal(criteria) {
        const modal = document.getElementById("criteriaModal");
        const criteriaDetails = document.getElementById("criteriaDetails");
        const subcriteriaDetails = document.getElementById("subcriteriaDetails");

        criteriaDetails.textContent = criteria;
        subcriteriaDetails.innerHTML = ''; // Clear previous subcriteria details

        // Fetch subcriteria details via AJAX
        fetch(`../Backend/fetch_subcriteria.php?criteria=${encodeURIComponent(criteria)}&client_id=<?php echo $client_id; ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(subcriteria => {
                        const tr = document.createElement('tr');
                        const tdName = document.createElement('td');
                        tdName.textContent = subcriteria.subcriteria_criterion;
                        const tdRating = document.createElement('td');
                        tdRating.textContent = subcriteria.subrating_score;

                        // Apply color based on rating
                        const rating = parseFloat(subcriteria.subrating_score);
                        if (rating > 3.5) {
                            tdRating.style.backgroundColor = '#8fbc8f'; // Passed - lighter green
                            tdRating.style.color = '#000000';   
                        } else if (rating >= 3 && rating <= 3.5) {
                            tdRating.style.backgroundColor = '#c0c0c0'; // Conditional - lighter yellow
                            tdRating.style.color = '#000000';
                        } else {
                            tdRating.style.backgroundColor = '#fa8072'; // Failed - lighter red
                            tdRating.style.color = '#000000';
                        }

                        tr.appendChild(tdName);
                        tr.appendChild(tdRating);
                        subcriteriaDetails.appendChild(tr);
                    });
                } else {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 2;
                    td.textContent = 'No data found.';
                    tr.appendChild(td);
                    subcriteriaDetails.appendChild(tr);
                }
            })
            .catch(error => {
                console.error('Error fetching subcriteria:', error);
                subcriteriaDetails.textContent = 'Error fetching subcriteria.';
            });

        modal.style.display = "block";
    }

    function closeCriteriaModal() {
        const modal = document.getElementById("criteriaModal");
        modal.style.display = "none";
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById("criteriaModal");
        if (event.target === modal) {
            closeCriteriaModal();
        }
    });

document.getElementById('logoutBtn').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to log out?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, log out',
            cancelButtonText: 'No, stay logged in',
            confirmButtonColor: '#e77373',
            cancelButtonColor: '#6c757d',
            customClass: {
                popup: 'custom-logout-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../Backend/LoginSystemBackend/logout.php';
            }
        });
    });

document.addEventListener('DOMContentLoaded', function () {
        const editBtn = document.getElementById('editProfileBtn');
        const saveBtn = document.getElementById('saveProfileBtn');
        const cancelBtn = document.getElementById('cancelEditBtn');
        const viewDetailsBtn = document.getElementById('moreDetailsBtn'); // View Contact Details button

        const fields = [
            { id: 'nameField', name: 'client_name' },
           
            { id: 'sectorNameField', name: 'sector_name', isDropdown: true }, // Mark sectorNameField as dropdown
            { id: 'locationField', name: 'client_location' }
        ];

        let originalValues = {};
        let isEditing = false;

        editBtn.addEventListener('click', function () {
            if (!isEditing) {
                originalValues = {};
                fields.forEach(field => {
                    const element = document.getElementById(field.id);
                    originalValues[field.id] = element.textContent;

                    if (field.isDropdown) {
                        element.innerHTML = `
                            <select class="form-select" name="${field.name}">
                                <option value="School" ${element.textContent.trim() === 'School' ? 'selected' : ''}>School</option>
                                <option value="Government" ${element.textContent.trim() === 'Government' ? 'selected' : ''}>Government</option>
                                <option value="Sponsor" ${element.textContent.trim() === 'Sponsor' ? 'selected' : ''}>Sponsor</option>
                                <option value="Industry" ${element.textContent.trim() === 'Industry' ? 'selected' : ''}>Industry</option>
                            </select>
                        `;
                    } else if (field.type === 'number') {
                        element.innerHTML = `<input type="text" class="form-control" name="${field.name}" value="${element.textContent.trim()}" oninput="this.value = this.value.replace(/[^0-9\-]/g, '')">`;
                    } else {
                        element.innerHTML = `<input type="text" class="form-control" name="${field.name}" value="${element.textContent.trim()}">`;
                    }
                });
                toggleEditState(true);
            }
        });

        cancelBtn.addEventListener('click', function () {
            restoreOriginalValues();
            toggleEditState(false);
            isEditing = false; 
        });

        saveBtn.addEventListener('click', function () {
            const changes = [];
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                const input = element.querySelector('input, select'); 
                const oldValue = originalValues[field.id];
                let newValue = input ? input.value.trim() : element.textContent.trim();

                if (!newValue) {
                    newValue = "Not Available";
                }

                if (oldValue !== newValue) {
                    changes.push({ label: field.name.replace('_', ' '), oldValue, newValue });
                }

                if (field.isDropdown) {
                    element.innerHTML = `
                        <select class="form-select" name="${field.name}">
                            <option value="School" ${newValue === 'School' ? 'selected' : ''}>School</option>
                            <option value="Government" ${newValue === 'Government' ? 'selected' : ''}>Government</option>
                            <option value="Sponsor" ${newValue === 'Sponsor' ? 'selected' : ''}>Sponsor</option>
                            <option value="Industry" ${newValue === 'Industry' ? 'selected' : ''}>Industry</option>
                        </select>
                    `;
                } else {
                    element.textContent = newValue;
                }
            });

            if (changes.length > 0) {
                openChangesModal(changes);
            } else {
                Swal.fire({
                    title: 'No Changes',
                    text: 'No changes were made.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6c757d'
                }).then(() => {
                    location.reload(); // Refresh the page when no changes are made
                });
            }
        });

        function toggleEditState(editing) {
            isEditing = editing;

            const editBtn = document.getElementById('editProfileBtn');
            const saveBtn = document.getElementById('saveProfileBtn');
            const cancelBtn = document.getElementById('cancelEditBtn');
            const viewDetailsBtn = document.getElementById('moreDetailsBtn'); // View Contact Details button

            editBtn.style.display = editing ? 'none' : 'inline-block';
            saveBtn.style.display = editing ? 'inline-block' : 'none';
            cancelBtn.style.display = editing ? 'inline-block' : 'none';
            viewDetailsBtn.style.display = editing ? 'none' : 'inline-block'; // Toggle View Contact Details button

            if (!editing) {
                fields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (field.isDropdown) {
                        element.innerHTML = originalValues[field.id];
                    } else {
                        element.textContent = originalValues[field.id];
                    }
                });
            }
        }

        function restoreOriginalValues() {
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) {
                    element.innerHTML = originalValues[field.id] || 'N/A';
                }
            });
        }

        function openChangesModal(changes) {
            const changesList = document.getElementById('changesList');
            changesList.innerHTML = '';

            changes.forEach(change => {
                const li = document.createElement('li');
                li.textContent = `${change.label}: ${change.oldValue} → ${change.newValue}`;
                changesList.appendChild(li);
            });

            const modal = document.getElementById('changesModal');
            modal.style.display = 'block';

      
            toggleEditState(true);
        }

     
        window.closeChangesModal = function () {
            const modal = document.getElementById('changesModal');
            modal.style.display = 'none';

            toggleEditState(true);
        };

        window.confirmChanges = function () {
            closeChangesModal();
            saveChangesToServer();
        };

        function saveChangesToServer() {
            const formData = new FormData();
            formData.append('action', 'update_client');
            formData.append('client_id', clientId);

            fields.forEach(field => {
                const input = document.querySelector(`#${field.id} input, #${field.id} select`);
                let value = input ? input.value.trim() : document.getElementById(field.id).textContent.trim();

                if (!value) {
                    value = "Not Available";
                }

                formData.append(field.name, value);
            });

            fetch('../Backend/fetch_data_creosales.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        fields.forEach(field => {
                            const element = document.getElementById(field.id);
                            const input = document.querySelector(`#${field.id} input, #${field.id} select`);
                            if (field.isDropdown) {
                                element.innerHTML = `
                                    <select class="form-select" name="${field.name}">
                                        <option value="School" ${input.value === 'School' ? 'selected' : ''}>School</option>
                                        <option value="Government" ${input.value === 'Government' ? 'selected' : ''}>Government</option>
                                        <option value="Sponsor" ${input.value === 'Sponsor' ? 'selected' : ''}>Sponsor</option>
                                        <option value="Industry" ${input.value === 'Industry' ? 'selected' : ''}>Industry</option>
                                    </select>
                                `;
                            } else {
                                element.textContent = input ? input.value : element.textContent;
                            }
                        });
                        Swal.fire('Success', 'Profile updated successfully!', 'success').then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                })
                .finally(() => {
                    toggleEditState(false);
                });
        }
    });