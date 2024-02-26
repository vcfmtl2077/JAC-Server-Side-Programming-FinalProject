document.addEventListener('DOMContentLoaded', function() {
    fetchOrder();
    
});

function fetchOrder() {
    var currentUserName = JSON.parse(localStorage.getItem('currentUserName')).username; 
    fetch('profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username: currentUserName })
    })
    .then(response => response.json())
    .then(data => displayOrders(data))
    .catch(error => console.error('Error fetching orders:', error));
}

function displayOrders(orders) {
    const orderTableBody = document.getElementById('order-list');
    orderTableBody.innerHTML = '';

    orders.forEach((order, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${order.order_id}</td>
            <td>${order.order_date}</td>
            <td>${order.name}</td>
            <td>${order.quantity}</td>
            <td>${order.total_amount}</td>
            <td>${order.status}</td>
            
        `;
        orderTableBody.appendChild(row);
    });
}
