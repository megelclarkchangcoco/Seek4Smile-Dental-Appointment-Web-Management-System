const searchBar = document.querySelector(".search input");
const searchBtn = document.querySelector(".search button");
const usersList = document.querySelector(".all-users");

searchBar.onkeyup = () => {
    let searchTerm = searchBar.value.trim();
    if(searchTerm !== "") {
        searchBar.classList.add("active");
        
        fetch('php/search_patients.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchTerm=' + searchTerm
        })
        .then(response => response.text())
        .then(data => usersList.innerHTML = data)
        .catch(error => console.error('Error:', error));
    } else {
        searchBar.classList.remove("active");
        fetch('php/get_online_patients.php')
        .then(response => response.text())
        .then(data => usersList.innerHTML = data);
    }
}

function updateUsersList() {
    if(!searchBar.classList.contains("active")) {
        fetch('php/get_online_patients.php')
        .then(response => response.text())
        .then(data => usersList.innerHTML = data);
    }
}

setInterval(updateUsersList, 500);