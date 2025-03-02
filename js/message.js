const searchBar = document.querySelector(".search input");
const searchBtn = document.querySelector(".search button");
const usersList = document.querySelector(".all_users");

// Search functionality
searchBar.onkeyup = () => {
    let searchTerm = searchBar.value.trim();
    if(searchTerm !== "") {
        searchBar.classList.add("active");
        
        // Send search request
        fetch('php/search.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchTerm=' + searchTerm
        })
        .then(response => response.text())
        .then(data => {
            usersList.innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    } else {
        searchBar.classList.remove("active");
    }
}

// Update users list periodically
function updateUsersList() {
    if(!searchBar.classList.contains("active")) {
        fetch('php/get_users.php')
        .then(response => response.text())
        .then(data => {
            usersList.innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }
}

// Update status
function updateStatus(status) {
    fetch('php/update_status.php?status=' + status, {
        method: 'POST'
    });
}

// Update users list every 500ms
setInterval(updateUsersList, 500);

// Update status when page loads
window.onload = () => updateStatus('Online');

// Update status when user leaves
window.onbeforeunload = () => updateStatus('Offline');