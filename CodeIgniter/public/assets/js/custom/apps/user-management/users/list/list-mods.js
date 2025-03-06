document.getElementById('resetFilters').addEventListener('click', function() {
    // Get the form element
    const form = document.getElementById('form-filter');

    // Clear all text input fields
    const textInputs = form.querySelectorAll('input[type="text"]');
    textInputs.forEach(input => {
        input.value = ''; // Set each input's value to an empty string
    });

    // Clear all select elements
    const selectElements = form.querySelectorAll('select');
    selectElements.forEach(select => {
        select.selectedIndex = 0; // Reset to the first option (usually empty)
    });

    // Construct the new URL preserving sorting
    const urlParams = new URLSearchParams(window.location.search);
    const sortField = urlParams.get('sortField');
    const sortOrder = urlParams.get('sortOrder');

    let newUrl = window.location.pathname; // Keep the base URL
    if (sortField && sortOrder) {
        newUrl += `?sortField=${sortField}&sortOrder=${sortOrder}`;
    }

    // Redirect to the new URL
    window.location.href = newUrl;
});