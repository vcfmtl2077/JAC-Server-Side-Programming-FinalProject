var ls = new SecureLS({ encodingType: 'aes'});//, isCompression: false, encryptionSecret: 'test' });

const signupForm = document.getElementById("signupForm");
const loginForm = document.getElementById("loginForm");
const loginLink = document.getElementById("loginLink");

document.addEventListener('DOMContentLoaded', function () {

    if (signupForm) {
        populateYears();
        populateMonths();
        populateDays();

        document.getElementById('year').onchange = function () {
            populateDays();
        }

        document.getElementById('month').onchange = function () {
            populateDays();
        }

        signupForm.addEventListener("submit", function (event) {
            event.preventDefault();
            addUser();
        });
    }

    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            authenticate();
        });

        var signupButton = document.getElementById('signupButton');
        if (signupButton) {
            signupButton.addEventListener('click', function () {
                window.location.href = 'signup.html';
            });
        }
    }

    if (loginLink) {
        updateLoginStatus();
        updateUserProfile(); 
    }
})

function populateYears() {
    var yearSelect = document.getElementById('year');
    var currentYear = new Date().getFullYear();
    for (var i = currentYear; i >= 1900; i--) {
        var option = document.createElement('option');
        option.textContent = i;
        yearSelect.appendChild(option);
    }
}

function populateMonths() {
    var monthSelect = document.getElementById('month');
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
        'August', 'September', 'October', 'November', 'December'];
    months.forEach(function (month, index) {
        var option = document.createElement('option');
        option.value = index + 1;
        option.textContent = month;
        monthSelect.appendChild(option);
    });
}

function populateDays() {
    var daySelect = document.getElementById('day');
    var monthSelect = document.getElementById('month');
    var yearSelect = document.getElementById('year');
    var year = yearSelect.value;
    var month = monthSelect.value;
    var dayCount;
    if (month === '2') {
        // Check for leap year
        if ((year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0)) {
            dayCount = 29;
        } else {
            dayCount = 28;
        }
    } else if (month === '4' || month === '6' || month === '9' || month === '11') {
        dayCount = 30;
    } else {
        dayCount = 31;
    }

    daySelect.innerHTML = '';
    for (var i = 1; i <= dayCount; i++) {
        var option = document.createElement('option');
        option.textContent = i;
        daySelect.appendChild(option);
    }
}

function addUser() {
    let user = {
        username: document.getElementById("userName").value,
        password: document.getElementById("password").value,
        firstname: document.getElementById("firstName").value,
        lastname: document.getElementById("lastName").value,
        email: document.getElementById("email").value,
        phone: document.getElementById("telephone").value,
        dobDay: document.getElementById("day").value,
        dobMonth: document.getElementById("month").value,
        dobYear: document.getElementById("year").value,

    };
    console.log('User info before encryption:', user);


    if (validUserName(user.username) && validPassword(user.password) && validPhone(user.phone)) {
        ls.set(user.username, user); // Data is automatically encrypted
        alert("Sign up successful!");
        window.location.href = 'login.html';
    }

    console.log('User info after decryption:', localStorage.getItem(user.username));
    console.log('Decrypted data:', ls.get(user.username));

};

function validUserName(username) {
    if (username.length > 50) {
        alert('Username must be less than 50 characters.');
        return false;
    }
    return true;
}

function validPassword(password) {
    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
    if (!passwordPattern.test(password)) {
        alert("Password must contain numbers, uppercase and lowercase letters, and symbols, and must be at least 8 characters long.");
        return false;
    }
    return true;
}

function validPhone(phone) {
    var phonePattern = /^\+?(\d{1,3})?[-. ]?\(?\d{1,3}\)?[-. ]?\d{1,4}[-. ]?\d{1,4}[-. ]?\d{1,9}$/;
    if (!phonePattern.test(phone)) {
        alert("The phone number does not meet the requirements, please re-enter.");
        return false;
    }
    return true;
}

function authenticate() {
    let username = document.getElementById("userName").value;
    let password = document.getElementById("password").value;
    let user = ls.get(username);
    if (user && user.password === password) {
        alert("Login successful!");
        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('currentUserName', username);

        window.location.href = 'profile.html'; 
        //window.location.href = 'contact_us.html';

    } else {
        // document.getElementById("errorMessage").style.display = "block";
        alert("Username or Password Invalid");
        return;
    }
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
            };
        } else {
            loginLink.textContent = 'Login';
            loginLink.href = 'login.html';
            loginLink.onclick = null;
        }
    }
}

function updateUserProfile() {
    let isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    let username = localStorage.getItem('currentUserName'); 
    let userData = ls.get(username); 
    if (isLoggedIn && userData) {
        document.getElementById('userFullName').textContent = `${userData.firstname} ${userData.lastname}`;
        document.getElementById('userName').textContent = userData.username;
        document.getElementById('userPhone').textContent = userData.phone;
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
