
document.addEventListener('DOMContentLoaded', function() {
    fetchCustomers();
    var currentUserName = JSON.parse(localStorage.getItem('currentUserName')); 
    if (currentUserName) {
        document.getElementById('username').textContent = currentUserName.username;
    }

    document.getElementById('add-button').addEventListener('click', function() {
        addNewCustomerRow();
    });

    document.getElementById('logout-button').addEventListener('click', function() {
        logoutUser();
    });

    document.getElementById('customer-list').addEventListener('click', function(e) {
        const customerId = e.target.getAttribute('data-id');

        if (e.target.classList.contains('save-button')) {
            const row = e.target.closest('tr');
            const customer_id = row.querySelector('.customer-id').value;
            const username = row.querySelector('.username').value;
            const password = row.querySelector('.password').value;
            const firstName = row.querySelector('.first-name').value;
            const lastName = row.querySelector('.last-name').value;
            const dob = row.querySelector('.dob').value;
            const email = row.querySelector('.email').value;
            const telephone = row.querySelector('.telephone').value;
            const city = row.querySelector('.city').value;
            const province = row.querySelector('.province').value;
            const postcode = row.querySelector('.postcode').value;
            editCustomer(customer_id,username, password, firstName, lastName, dob, email, telephone, city, province, postcode);
        }
        else if (e.target.classList.contains('delete-button')) {
            deleteCustomer(customerId);
        }

        if (e.target.classList.contains('create-button')) {
            const row = e.target.closest('tr');
            const username = row.querySelector('.username').value;
            const password = row.querySelector('.password').value;
            const firstName = row.querySelector('.first-name').value;
            const lastName = row.querySelector('.last-name').value;
            const dob = row.querySelector('.dob').value;
            const email = row.querySelector('.email').value;
            const telephone = row.querySelector('.telephone').value;
            const city = row.querySelector('.city').value;
            const province = row.querySelector('.province').value;
            const postcode = row.querySelector('.postcode').value;
            createCustomer(username, password, firstName, lastName, dob, email, telephone, city, province, postcode);
        
            row.remove(); 
        }
    });
});

function fetchCustomers() {
    fetch('customerList.php?action=fetch')
        .then(response => response.json())
        .then(data => displayCustomers(data))
        .catch(error => console.error('Error fetching customers:', error));
}

function displayCustomers(customers) {
    const customerTableBody = document.getElementById('customer-list');
    customerTableBody.innerHTML = '';

    customers.forEach((customer, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><input type="text" class="customer-id" value="${customer.customer_id}" readonly style="background-color: #e0e0e0;">></td>
            <td><input type="text" class="username" value="${customer.username}" readonly style="background-color: #e0e0e0;">></td>
            <td><input type="password" class="password" value="${customer.password}"  required></td>
            <td><input type="text" class="first-name" value="${customer.first_name}"  required></td>
            <td><input type="text" class="last-name" value="${customer.last_name}" required></td>
            <td><input type="date" class="dob" value="${customer.dob}" required></td>
            <td><input type="text" class="email" value="${customer.email}"></td>
            <td><input type="text" class="telephone" value="${customer.telephone}"></td>
            <td><input type="text" class="city" value="${customer.city}"></td>
            <td><input type="text" class="province" value="${customer.province}"></td>
            <td><input type="text" class="postcode" value="${customer.postcode}"></td>
            <td>
                <button class="save-button" data-id="${customer.customer_id}">Save</button>
                <button class="delete-button" data-id="${customer.customer_id}">Delete</button>
            </td>

        `;
        customerTableBody.appendChild(row);

    //     <td>
    //     <div class="dropdown">
    //         <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
    //             <i class="bx bx-dots-vertical-rounded"></i>
    //         </button>
    //         <div class="dropdown-menu">
    //             <a class="dropdown-item" href="javascript:void(0);" data-id="${customer.customer_id}"><i class="bx bx-edit-alt me-1"></i> Save</a>
    //             <a class="dropdown-item" href="javascript:void(0);" data-id="${customer.customer_id}"><i class="bx bx-trash me-1"></i> Delete</a>
    //         </div>        
    //     </div>
    // </td>
    });
}

function editCustomer(customerId,username, password, firstName, lastName, dob, email, telephone, city, province, postcode) {

    if (!validUsername(username) || !validName(firstName) || !validName(lastName) || !validPhone(telephone) || !validPassword(password) ||
        !validDOB(dob) || !validEmail(email) || !validCity(city) || !validProvince(province) || !validPostcode(postcode)) {
            return; 
    }
    
    const customerData = {
        customer_id: customerId,
        username: username,
        password: password,
        first_name: firstName, 
        last_name: lastName,
        dob: dob, 
        email: email,
        telephone: telephone,
        city: city, 
        province: province,
        postcode: postcode,
        // registration_date: "" 
    };

    fetch('customerList.php?action=edit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(customerData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            fetchCustomers();
            alert("Update customer successfuly.");
        } else {
            console.error('Failed to edit customer');
        }
    })
    .catch(error => console.error('Error editing customer:', error));
}

function deleteCustomer(customerId) {
    const customerData = {customer_id: customerId };

    fetch('customerList.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(customerData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            fetchCustomers();
        } else {
            console.error('Failed to delete customer');
        }
    })
    .catch(error => console.error('Error deleting customer:', error));
}

function addNewCustomerRow() {
    const customerTableBody = document.getElementById('customer-list');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td></td>
        <td><input type="text" class="customer-id"  readonly style="background-color: #e0e0e0;"></td>
        <td><input type="text" class="username" required></td>
        <td><input type="password" class="password" required></td>
        <td><input type="text" class="first-name" required></td>
        <td><input type="text" class="last-name" required></td>
        <td><input type="date" class="dob" required></td>
        <td><input type="email" class="email" ></td>
        <td><input type="tel" class="telephone" ></td>
        <td><input type="text" class="city" ></td>
        <td><input type="text" class="province" ></td>
        <td><input type="text" class="postcode" ></td>
        <td>
            <button class="create-button">Create</button>
            <button class="abort-button">Abort</button>
        </td>
    `;
    customerTableBody.appendChild(newRow);
    newRow.querySelector('.abort-button').addEventListener('click', function() {
        newRow.remove();
    });
}

// validate
function validUsername(username) {
    if (username === "") {
        alert('Username is required.');
        return false;
    }
    if (username.length > 255) {
        alert('Username must be less than 255 characters.');
        return false;
    }
    return true;
}

function validName(name) {
    if (name === "") {
        alert('Name is required.');
        return false;
    }
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

function validEmail(email) {
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email (e.g., example@example.com).");
        return false;
    }else if (email.length > 100) {
        alert('Email must be less than 100 characters.');
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
        alert("Please enter a valid postcode (e.g., J7W 9Z9).");
        return false;
    }else if (postcode.length > 20) {
        alert('Postcode must be less than 20 characters.');
        return false;
    }
    return true;
}

function createCustomer(username, password, firstName, lastName, dob, email, telephone, city, province, postcode) {
    if (!validUsername(username) || !validName(firstName) || !validName(lastName) || !validPhone(telephone) || !validPassword(password) ||
        !validDOB(dob) || !validEmail(email) || !validCity(city) || !validProvince(province) || !validPostcode(postcode)) {
    return; 
}
    const customerData = {
        username: username, 
        password: password,
        first_name: firstName,
        last_name: lastName,
        dob: dob,
        email: email,
        telephone: telephone,
        city: city,
        province: province,
        postcode: postcode,
        registration_date: ""
    };
    
    fetch('customerList.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(customerData)
    })
    .then(response => response.json())
    .then(data => {
        console.log("data",data)
        if (data?.success) {
            fetchCustomers(); 
            alert("Add new customer successfuly.");
        } else {
            console.error('Failed to create customer');
        }
    })
    .catch(error => console.error('Error creating customer:', error));
}

function logoutUser() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('currentUserName');
    window.location.href = 'index.html';
    // updateLoginStatus();

    // if (!location.pathname.includes('contact_us.html')) {
    //     window.location.href = 'index.html';
    // }
}