// Function to open the modal and set the appointment ID
function openModal(appointmentID) {
    document.getElementById('appointmentID').value = appointmentID; // Set the appointment ID
    document.getElementById('cancelModal').style.display = 'block'; // Show the modal
}

// Function to close the modal
function closeModal() {
    document.getElementById('cancelModal').style.display = 'none'; // Hide the modal
}

// Function to submit the cancel request
function submitCancel() {
    const appointmentID = document.getElementById('appointmentID').value;
    const reason = document.querySelector('input[name="reason"]:checked')?.value || '';
    const otherReason = document.getElementById('otherReason').value;

    // Combine the selected reason and other reason
    const cancelationReason = otherReason ? `${reason}: ${otherReason}` : reason;

    // Validate if a reason is selected
    if (!reason && !otherReason) {
        alert('Please select a reason or provide additional details.');
        return;
    }

    // Send data to the server using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/cancel_appointment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('Appointment canceled successfully.');
            location.reload(); // Refresh the page to reflect changes
        } else {
            alert('Failed to cancel appointment.');
        }
    };
    xhr.send(`appointmentID=${appointmentID}&cancelationReason=${encodeURIComponent(cancelationReason)}`);
}

// Close the modal if the user clicks outside of it
window.onclick = function (event) {
    const modal = document.getElementById('cancelModal');
    if (event.target === modal) {
        closeModal();
    }
};