function validateTowForm() {
    const vehicleId = document.getElementById('vehicle_id').value;
    const pickup = document.getElementById('pickup_location').value.trim();
    const dropoff = document.getElementById('dropoff_location').value.trim();
    const contact = document.getElementById('contact').value.trim();

    if (!vehicleId) {
        alert("Please select a vehicle.");
        return false;
    }
    if (!pickup) {
        alert("Please provide a pickup location.");
        return false;
    }
    if (!contact) {
        alert("Please provide a contact number.");
        return false;
    }

    return true;
}
