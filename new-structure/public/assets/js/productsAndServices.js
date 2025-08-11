document.addEventListener("DOMContentLoaded", () => {
    loadTable();
    initEdit();

    //EDIT
    // document.querySelectorAll('.edit-btn').forEach(button => {
    //     button.addEventListener('click', function() {
    //         const row = this.closest('tr');
    //         document.getElementById('products_id').value = row.dataset.id;
    //         document.getElementById('products_item').value = row.dataset.item;
    //         document.getElementById('products_description').value = row.dataset.description;
    //         document.getElementById('products_cost').value = row.dataset.cost;
    //         document.getElementById('products_srp').value = row.dataset.srp;
    //         document.getElementById('services_id').value = row.dataset.service;
    //     });
    // });

    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent default form submit

        const form = document.getElementById('editForm');
        const data = Object.fromEntries(new FormData(form).entries());

        try {
            const response = await fetch(
                'http://localhost:8080/Creosales/new-structure/backend/api/product/', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
            const result = await response.json();
            console.log('Parsed Response: ', result);
            if (result.success === true) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                this.reset();
                location.reload();
            } else throw new Error(result.message);
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        }
    });

    //ADD 
    document.getElementById('addForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const data = new FormData(this);

        try {
            const response = await fetch(
                'http://localhost:8080/Creosales/new-structure/backend/api/product/', {
                    method: 'POST',
                    body: data
                });

            const result = await response.json();
            console.log('Parsed Response: ', result);
            if (result.success === true) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                modal.hide();
                this.reset();
                location.reload();
            } else throw new Error(result.message);
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        }
    });

    //DELETE
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();

                const products_id = {
                    products_id: this.dataset.id
                };

                if (!confirm(
                        `Are you sure you want to delete product with product ID ${products_id['products_id']}?`
                    )) return;

                try {
                    const response = await fetch(
                        'http://localhost:8080/Creosales/new-structure/backend/api/product/', {
                            method: 'DELETE',
                            body: JSON.stringify(products_id)
                        });

                    const result = await response.json();

                    if (result.success === true) {
                        location.reload();
                    } else throw new Error(result.message);
                } catch (error) {
                    console.error('Error: ', error);
                    alert('An error ocurred while processing your request.');
                }
            })
        })
    })
});

async function loadTable() {
    // Loads the product data from the API and adds it to the table
    const tableBody = document.querySelector("#productsTable tbody");

    try {
        // Fetches product data from the API
        const response = await fetch("http://localhost/Creosales/Creosales/new-structure/backend/api/product/");
        if (!response.ok) throw new Error(response.message);
        const result = await response.json();
        const data = result.data;

        // Adds a row for each product retrieved
        data.forEach(product => {
            const row = document.createElement("tr");
            row.dataset.id = `${product.products_id}`;
            row.dataset.item = `${product.products_item}`;
            row.dataset.description = `${product.products_description}`;
            row.dataset.cost = `${product.products_cost}`;
            row.dataset.srp = `${product.products_srp}`;
            row.dataset.service = `${product.services_id}`;
            row.innerHTML = `
                <td>${product.products_id}</td>
                <td>${product.products_item}</td>
                <td>${product.products_description}</td>
                <td>${product.products_cost}</td>
                <td>${product.products_srp}</td>
                <td>${product.services_id}</td>
                <td>
                    <a id="btn-white" href="#" data-bs-toggle="modal" data-bs-target="#editModal" class="edit-btn">
                        <i class="fas fa-edit"></i>
                    </a>
                    |
                    <a id="btn-white" href="#" data-id="${product.products_id}" class="delete-btn">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch(error) {
        console.error("Error loading table: ", error);
        tableBody.innerHTML = "Failed to load data";
    }
}

function initEdit() {
    // Populates the edit form with the product data from the row it's located
    document.querySelector('#productsTable tbody').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('edit-btn')) {
            const row = e.target.closest('tr');
            document.getElementById('products_id').value = row.dataset.id;
            document.getElementById('products_item').value = row.dataset.item;
            document.getElementById('products_description').value = row.dataset.description;
            document.getElementById('products_cost').value = row.dataset.cost;
            document.getElementById('products_srp').value = row.dataset.srp;
            document.getElementById('services_id').value = row.dataset.service;
        }
    });
}