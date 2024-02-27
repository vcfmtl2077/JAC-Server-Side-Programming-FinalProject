function getALlRole() {
    var baseUrl = window.location.origin;  // Gets 'http://localhost:9000'
    var apiUrl = baseUrl + "/api/v1/permission";
    $.get(apiUrl, function(data) {
        // Assuming 'data' is an array of objects with role_id, role_name, role_status
        var tableBody = $('#roleList');
        tableBody.empty(); // Clear existing table rows

        // Convert data from string to JSON if necessary
        if (typeof data === 'string') {
            data = JSON.parse(data);
        }

        // Use a for loop instead of $.each
        for (var i = 0; i < data.length; i++) {
            var role = data[i];
            var row = $('<tr></tr>');
            row.append(`
            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>${role.role_id}</strong></td>
            <td>${role.role_name}</td>
            <td>
              <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                  class="avatar avatar-xs pull-up" title="Lilian Fuller">
                  <img src="../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                </li>
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                  class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                  <img src="../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle" />
                </li>
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                  class="avatar avatar-xs pull-up" title="Christina Parker">
                  <img src="../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle" />
                </li>
              </ul>
            </td>
            <td><span class="badge bg-label-primary me-1">${role.role_status}</span></td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                  data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#addRoleModal"
                    data-bs-toggle="modal"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                    Delete</a>
                </div>
              </div>
            </td>
            `);
            tableBody.append(row);
        }
    });
}
