function validateBookingForm() {
    const vehicle = document.getElementById('vehicle_id').value;
    const service = document.getElementById('service_type').value;
    const dateInput = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const address = document.getElementById('address').value.trim();
    const contact = document.getElementById('contact').value.trim();

    if (!vehicle) {
        alert("Please select a vehicle.");
        return false;
    }
    if (!service) {
        alert("Please select a service type.");
        return false;
    }
    if (!dateInput) {
        alert("Please select a date.");
        return false;
    }
    
    // Check if date is in the past
    const today = new Date();
    today.setHours(0,0,0,0);
    const selectedDate = new Date(dateInput);
    if (selectedDate < today) {
        alert("Service date cannot be in the past.");
        return false;
    }

    if (!time) {
        alert("Please select a time.");
        return false;
    }
    if (!address) {
        alert("Please enter the service address.");
        return false;
    }
    if (!contact) {
        alert("Please provide a contact number.");
        return false;
    }

    // Basic phone validation (just length check for simplicity)
    if (contact.length < 5) {
        alert("Please enter a valid contact number.");
        return false;
    }

    return true;
}
