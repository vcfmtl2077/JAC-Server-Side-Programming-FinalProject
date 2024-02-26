const url = "http://localhost:9000/api/v1/employee";

var userRole = "admin"; 
    
    window.onload = function() {
        checkUserRole();
    };

    
    function checkUserRole() {
        if (userRole === "admin") {
            
            console.log("用户是管理员，允许打开页面");
        } else {
            
            console.log("用户不是管理员，正在重定向...");
            window.location.href = "./admin-404-page.html"; // 将此处的 other_page.html 替换为目标页面的 URL
        }
    }



document.addEventListener("DOMContentLoaded", function() {
    loadData();
});

function loadData() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            populateTable(data);
        }
    };
    xhr.send();
}

function populateTable(data) {
    var tableBody = document.querySelector("#dataTable tbody");
    tableBody.innerHTML = "";

    data.forEach(function(item) {
        var row = document.createElement("tr");
        row.innerHTML = "<td>" + item.employee_id + "</td><td>" 
                        + item.email + "</td><td>" 
                        + item.first_name + "</td><td>" 
                        + item.last_name + "</td><td>" 
                        + item.phone_number 
                        + "</td><td><button>Edit</button>  <button>Delete</button></td>";;
        tableBody.appendChild(row);
    });
}