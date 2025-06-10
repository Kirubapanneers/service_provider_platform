document.addEventListener('DOMContentLoaded', () => {
    // Select all elements with the class 'show-more'
    const showMoreLinks = document.querySelectorAll('.show-more');

    // Iterate over each 'show-more' link
    showMoreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default anchor behavior

            // Find the closest parent with the class 'server-one'
            const serverOne = this.closest('.server-one');
            if (!serverOne) return; // Exit if no parent found

            // Select the '.additional-details' within the current 'server-one'
            const additionalDetails = serverOne.querySelector('.additional-details');
            if (!additionalDetails) return; // Exit if no additional details found

            // Toggle the display of '.additional-details'
            if (additionalDetails.style.display === 'block') {
                additionalDetails.style.display = 'none';
                this.textContent = 'Show more'; // Update link text
            } else {
                additionalDetails.style.display = 'block';
                this.textContent = 'Show less'; // Update link text
            }
        });
    });
});


// Back to Top Functionality
document.addEventListener('DOMContentLoaded', () => {
    const backToTopButton = document.getElementById('back-to-top');

    // Show the button when scrolled down 300px
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });

    // Scroll to top when the button is clicked
    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});



//filter
document.getElementById('filter-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get filter values
    const serviceType = document.getElementById('service-type').value;
    const priceRange = parseInt(document.getElementById('price-range').value);
    const location = document.getElementById('location').value;
    const warranty = document.getElementById('warranty').value;
    const availability = document.getElementById('availability').value;

    // Get all service blocks
    const serviceBlocks = document.querySelectorAll('.server-one');

    serviceBlocks.forEach(function(block) {
        const blockType = block.getAttribute('data-type');
        const blockPrice = parseInt(block.getAttribute('data-price'));
        const blockLocation = block.getAttribute('data-location');
        const blockWarranty = block.getAttribute('data-warranty');
        const blockAvailability = block.getAttribute('data-availability');

        let isVisible = true;

        // Filter by service type
        if (serviceType && serviceType !== blockType) {
            isVisible = false;
        }

        // Filter by price range
        if (!isNaN(priceRange) && blockPrice > priceRange) {
            isVisible = false;
        }

        // Filter by location
        if (location && location !== blockLocation) {
            isVisible = false;
        }

        // Filter by warranty
        if (warranty && warranty !== blockWarranty) {
            isVisible = false;
        }

        // Filter by availability
        if (availability && !checkAvailability(blockAvailability, availability)) {
            isVisible = false;
        }

        // Show or hide block based on filter
        if (isVisible) {
            block.style.display = 'block';
        } else {
            block.style.display = 'none';
        }
    });
});

// Helper function to check availability
function checkAvailability(blockAvailability, selectedAvailability) {
    const blockStartEnd = parseAvailabilityRange(blockAvailability);
    const selectedStartEnd = parseAvailabilityRange(selectedAvailability);

    const blockStart = blockStartEnd[0];
    const blockEnd = blockStartEnd[1];
    const selectedStart = selectedStartEnd[0];
    const selectedEnd = selectedStartEnd[1];

    // Check if the selected availability overlaps with the block's availability
    return (selectedStart < blockEnd && selectedEnd > blockStart);
}

// Helper function to parse availability time ranges
function parseAvailabilityRange(availabilityStr) {
    const [startStr, endStr] = availabilityStr.split('-to-');

    return [parseTime(startStr), parseTime(endStr)];
}

// Helper function to parse time into minutes since midnight
function parseTime(timeStr) {
    const [time, modifier] = timeStr.trim().split(' ');
    let [hours, minutes] = time.split(':').map(Number);

    if (isNaN(minutes)) {
        minutes = 0; // Default to 0 if no minutes provided
    }

    if (modifier === 'PM' && hours < 12) {
        hours += 12;
    }
    if (modifier === 'AM' && hours === 12) {
        hours = 0;
    }

    return hours * 60 + minutes; // Convert time to total minutes since midnight
}