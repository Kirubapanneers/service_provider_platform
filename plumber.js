document.addEventListener('DOMContentLoaded', function () {
    const showMoreLinks = document.querySelectorAll('a.show-more');

    showMoreLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default anchor link behavior

            const details = this.previousElementSibling;
            if (details.style.display === "none" || details.style.display === "") {
                details.style.display = "block";
                this.textContent = "Show less";
            } else {
                details.style.display = "none";
                this.textContent = "Show more";
            }
        });
    });
});



//search barr
// Add event listener for the search input
document.getElementById('search-keyword').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();  // Get input and convert to lowercase

    // Get all service providers
    const services = document.querySelectorAll('.server-one');

    // Loop through each service provider
    services.forEach(service => {
        // Get the service name, location, and services offered
        const serviceName = service.querySelector('.server-name').textContent.toLowerCase();
        const location = service.getAttribute('data-location').toLowerCase();
        const servicesOffered = service.querySelector('ul').textContent.toLowerCase();

        // Check if the keyword matches the service name, location, or services offered
        if (serviceName.includes(keyword) || location.includes(keyword) || servicesOffered.includes(keyword)) {
            service.style.display = 'block';  // Show matching service
        } else {
            service.style.display = 'none';  // Hide non-matching service
        }
    });
});



