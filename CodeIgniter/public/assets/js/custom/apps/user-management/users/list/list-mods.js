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

    // Optionally, you can also submit the form after resetting
    form.submit();
});