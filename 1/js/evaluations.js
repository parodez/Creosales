// Helper function to create the page URL

// Function to handle sorting
function sortBy(column, direction) {
    updateUrlParams('orderBy', column);
    updateUrlParams('orderDir', direction);
    window.location.reload();
}

// Function to apply filters using AJAX
function applyFilters() {
    const sector = document.getElementById('sectorFilter').value;
    const review = document.getElementById('reviewFilter').value;
    const search = document.getElementById('searchInput').value;
    const admin = document.getElementById('adminFilter') ? document.getElementById('adminFilter').value : '';

    const params = new URLSearchParams({
        sector: sector,
        review: review,
        search: search,
        admin: admin,
        page: 1 // Reset to first page when applying filters
    });

    fetch('evaluation_page.php?' + params.toString())
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            document.querySelector('.table-container').innerHTML = doc.querySelector('.table-container').innerHTML;
            document.querySelector('.pagination-container').innerHTML = doc.querySelector('.pagination-container').innerHTML;
        })
        .catch(error => console.error('Error:', error));
}

// Debounce function to limit the rate at which a function can fire
function debounce(func, delay) {
    let debounceTimer;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

// Event listener for search input (real-time search with debounce)
document.getElementById('searchInput').addEventListener('input', debounce(function() {
    applyFilters();
}, 500));

// Function to update URL parameters
function updateUrlParams(key, value) {
    const url = new URL(window.location.href);
    if (value) {
        url.searchParams.set(key, value);
    } else {
        url.searchParams.delete(key);
    }
    window.history.replaceState({}, '', url);
}

// Event listener for search input (press Enter key)
document.getElementById('searchInput').addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        applyFilters();
    }
});

// Add event listener for export button for admin
const exportAllClientsBtn = document.getElementById('exportAllClientsBtn');
if (exportAllClientsBtn) {
    exportAllClientsBtn.addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Export Data',
            text: 'Do you want to export all client data to Excel?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, export it!',
            cancelButtonText: 'No, cancel',
            confirmButtonColor: '#90EE90',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../Backend/ExportSystemBackend/export_all_clients.php';
            }
        });
    });
}

// Add event listener for export button for user
const exportEvaluatedAllClientsBtn = document.getElementById('exportEvaluatedAllClientsBtn');
if (exportEvaluatedAllClientsBtn) {
    exportEvaluatedAllClientsBtn.addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Export Data',
            text: 'Select the sector to export evaluated client data to Excel:',
            icon: 'question',
            showConfirmButton: false,
            showCloseButton: true, // Add close button
            footer: '<div style="display: flex; flex-wrap: wrap; justify-content: space-around; gap: 10px;">' +
                '<button id="exportIndustryBtn" class="swal2-styled export-btn">Industry</button>' +
                '<button id="exportSchoolBtn" class="swal2-styled export-btn">School</button>' +
                '<button id="exportGovernmentBtn" class="swal2-styled export-btn">Government</button>' +
                '<button id="exportSponsorBtn" class="swal2-styled export-btn">Sponsor</button>' +
                '<button id="exportAllBtn" class="swal2-styled export-btn export-btn-all">Export All Sectors</button>' +
                '</div>'
        });

        document.getElementById('exportAllBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to export all evaluated client data to Excel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#90EE90',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Backend/ExportSystemBackend/user_export_all.php';
                }
            });
        });

        document.getElementById('exportSchoolBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to export evaluated client data for School to Excel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#90EE90',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Backend/ExportSystemBackend/user_export_school.php';
                }
            });
        });

        document.getElementById('exportGovernmentBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to export evaluated client data for Government to Excel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#90EE90',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Backend/ExportSystemBackend/user_export_government.php';
                }
            });
        });

        document.getElementById('exportSponsorBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to export evaluated client data for Sponsor to Excel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#90EE90',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Backend/ExportSystemBackend/user_export_sponsor.php';
                }
            });
        });

        document.getElementById('exportIndustryBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to export evaluated client data for Industry to Excel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, export it!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#90EE90',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Backend/ExportSystemBackend/user_export_industry.php';
                }
            });
        });
    });
}

document.getElementById('logoutBtn').addEventListener('click', function(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to log out?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log out',
        cancelButtonText: 'No, stay logged in',
        confirmButtonColor: '#e77373',
        cancelButtonColor: '#6c757d',
        customClass: {
            popup: 'custom-logout-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../Backend/LoginSystemBackend/logout.php';
        }
    });
});