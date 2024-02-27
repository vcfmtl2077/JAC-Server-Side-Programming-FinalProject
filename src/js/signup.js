// var ls = new SecureLS({ encodingType: 'aes'});

const signupForm = document.getElementById("signupForm");
const loginForm = document.getElementById("loginForm");
const loginLink = document.getElementById("loginLink");

document.addEventListener('DOMContentLoaded', function () {

    if (signupForm) {
        signupForm.addEventListener("submit", function (event) {
            event.preventDefault();
            addUser();
        });
    }

    if (loginForm) {
        var roleSelect = document.getElementById('role');
        var createAccountSection = document.querySelector('.input-group.text-center');

        roleSelect.addEventListener('change', function() {
            if(this.value === 'employee') {
                createAccountSection.style.display = 'none';
            } else {
                createAccountSection.style.display = 'block';
            }
        });

        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            authenticate();
        });
    }

    if (loginLink) {
        updateLoginStatus();
        updateUserProfile(); 
    }
})

// validate
function validUsername(username) {
    if (username.length > 255) {
        alert('Username must be less than 255 characters.');
        return false;
    }
    return true;
}

function validName(name) {
    if (name.length > 50) {
        alert('Name must be less than 50 characters.');
        return false;
    }
    return true;
}

function validPassword(password) {
    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
    if (!passwordPattern.test(password)) {
        alert("Password must contain numbers, uppercase and lowercase letters, and symbols, and must be at least 8 characters long.");
        return false;
    }else if (password.length > 255) {
        alert('Password must be less than 255 characters.');
        return false;
    }
    return true;
    
}

function validPhone(phone) {
    var phonePattern = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    if (!phonePattern.test(phone)) {
        alert("Please enter a valid Canadian phone number (e.g., 123-456-7890 or (123) 456-7890).");
        return false;
    }else if (phone.length > 20) {
        alert('Phone number must be less than 20 characters.');
        return false;
    }
    return true;
    
}

function validDOB(dob) {
    var dobPattern = /^\d{4}-\d{2}-\d{2}$/;
    if (!dobPattern.test(dob)) {
        alert("Please enter DOB in YYYY-MM-DD format.");
        return false;
    }
    return true;
}

function validCity(city) {
    if (city.length < 2 || city.length > 100) {
        alert("City name must be between 2 and 100 characters.");
        return false;
    }
    return true;
}

function validProvince(province) {
    if (province.length < 2 || province.length > 50) {
        alert("Province name must be between 2 and 50 characters.");
        return false;
    }
    return true;
}

function validPostcode(postcode) {
    var postcodePattern = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/;
    if (!postcodePattern.test(postcode)) {
        alert("Please enter a valid postcode.");
        return false;
    }else if (postcode.length > 20) {
        alert('Postcode must be less than 20 characters.');
        return false;
    }
    return true;
}

function addUser() {
    let user = {
        username: document.getElementById("userName").value,
        password: document.getElementById("password").value,
        firstname: document.getElementById("firstName").value,
        lastname: document.getElementById("lastName").value,
        dob: document.getElementById("dob").value,
        email: document.getElementById("email").value,
        telephone: document.getElementById("telephone").value,
        city: document.getElementById("city").value,
        province: document.getElementById("province").value,
        postcode: document.getElementById("postcode").value,
    };

    if (!validUsername(user.username) || !validName(user.firstname) || !validName(user.lastname) || !validPhone(user.telephone) || !validPassword(user.password) ||
        !validDOB(user.dob) || !validCity(user.city) || !validProvince(user.province) || !validPostcode(user.postcode)) {
        return; 
    }

    
    fetch('register.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(user)
    })
    .then(response => response.json()) 
    .then(data => {

        console.log('singup received response:', data)
        if (data?.success) {
                alert("Sign up successful!");
                window.location.href = 'login.html'; 
        } else {
            alert("Sign up failed: " + data?.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred during sign up.");
    });
}


function authenticate() {
    let username = document.getElementById("userName").value;
    let password = document.getElementById("password").value;
    let role = document.getElementById('role').value;

    const payload = {
        username: username,
        password: password,
        role:role
    };

    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data?.username) {
            alert("Login successful!");
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('currentUserName', JSON.stringify(data));
            if(data?.role == 'user'){
                window.location.href = 'profile.html';
            }else {
                window.location.href = 'customer-list.html';
            }
        } else {
            alert("Username or Password Invalid");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred while trying to log in.");
    });
}


function updateLoginStatus() {
    if (loginLink) {
        let isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        if (isLoggedIn) {
            loginLink.textContent = 'Logout';
            loginLink.href = '#';
            loginLink.onclick = function (event) {
                event.preventDefault();
                logoutUser();
            }
        } else {
            loginLink.textContent = 'Login';
            loginLink.href = 'login.html';
            loginLink.onclick = null;
        }
    }
}

function updateUserProfile() {
    let isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    let userData = JSON.parse(localStorage.getItem('currentUserName')); 
    console.log('updateUserProfile',userData)
    if (isLoggedIn && userData) {
        document.getElementById('userFullName').textContent = `${userData.first_name} ${userData.last_name}`;
        document.getElementById('userName').textContent = userData.username;
        document.getElementById('userPhone').textContent = userData.telephone;
    }
  }
  
function logoutUser() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('currentUserName')
    updateLoginStatus();

    if (!location.pathname.includes('contact_us.html')) {
        window.location.href = 'index.html';
    }
}
