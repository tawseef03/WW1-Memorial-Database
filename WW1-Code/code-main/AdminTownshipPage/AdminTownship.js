document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchForm = document.getElementById('searchForm');
    const searchButton = document.getElementById('searchButton');
    const resetButton = document.getElementById('resetButton');
    const createRecordBtn = document.getElementById('createRecordBtn');
    const modalOverlay = document.getElementById('modalOverlay');

    // Modals
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    
    // Forms
    const createForm = document.getElementById('createRecordForm');
    const editForm = document.getElementById('editRecordForm');
    const deleteForm = document.getElementById('deleteRecordForm');

    // Close buttons
    document.querySelectorAll('.close-btn, .cancel-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            closeAllModals();
        });
    });

    // Event Listeners
    createRecordBtn.addEventListener('click', () => {
        showModal(createModal);
    });

    createForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(createForm);
        try {
            const response = await fetch('create_township.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if(data.success) {
                closeAllModals();
                loadRecords();
                showMessage('Township created successfully', 'success');
            } else {
                showMessage(data.message, 'error');
            }
        } catch(error) {
            showMessage('Error creating township', 'error');
        }
    });

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(editForm);
        try {
            const response = await fetch('edit_township.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if(data.success) {
                closeAllModals();
                loadRecords();
                showMessage('Township updated successfully', 'success');
            } else {
                showMessage(data.message, 'error');
            }
        } catch(error) {
            showMessage('Error updating township', 'error');
        }
    });

    deleteForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const recordId = document.getElementById('deleteRecordId').value;
        try {
            const response = await fetch('delete_township.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: recordId })
            });
            const data = await response.json();
            if(data.success) {
                closeAllModals();
                loadRecords();
                showMessage('Township deleted successfully', 'success');
            } else {
                showMessage(data.message, 'error');
            }
        } catch(error) {
            showMessage('Error deleting township', 'error');
        }
    });

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        loadRecords(new FormData(searchForm));
    });

    resetButton.addEventListener('click', () => {
        searchForm.reset();
        loadRecords();
    });

    // Functions
    async function loadRecords(searchData = null) {
        const url = searchData ? 
            'get_townships.php?' + new URLSearchParams(searchData).toString() : 
            'get_townships.php';
        
        try {
            const response = await fetch(url);
            const data = await response.json();
            
            const tbody = document.querySelector('.records-table tbody');
            tbody.innerHTML = '';

            if(data.length === 0) {
                document.querySelector('.display').innerHTML = '<p class="no-records">No records found.</p>';
                return;
            }

            data.forEach(record => {
                const row = createTableRow(record);
                tbody.appendChild(row);
            });

            // Reinitialize edit and delete buttons
            initializeActionButtons();

        } catch(error) {
            showMessage('Error loading records', 'error');
        }
    }

    function createTableRow(record) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${record.TownshipID}</td>
            <td>${record.TownshipName}</td>
            <td>${record.Location}</td>
            <td>${record.Description || ''}</td>
            <td>
                <div class="action-buttons">
                    <button class="edit-btn" data-id="${record.TownshipID}">Edit</button>
                    <button class="delete-btn" data-id="${record.TownshipID}">Delete</button>
                </div>
            </td>
        `;
        return row;
    }

    function showModal(modal) {
        modalOverlay.classList.add('active');
        modal.classList.add('active');
    }

    function closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('active');
        });
        modalOverlay.classList.remove('active');
    }

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `alert ${type}`;
        messageDiv.textContent = message;
        document.body.appendChild(messageDiv);
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    function initializeActionButtons() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const recordId = btn.dataset.id;
                fetchRecordAndShowEditModal(recordId);
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const recordId = btn.dataset.id;
                document.getElementById('deleteRecordId').value = recordId;
                showModal(deleteModal);
            });
        });
    }

    async function fetchRecordAndShowEditModal(recordId) {
        try {
            const response = await fetch(`get_township.php?id=${recordId}`);
            const data = await response.json();
            if(data) {
                document.getElementById('editRecordId').value = data.TownshipID;
                document.getElementById('editTownshipName').value = data.TownshipName;
                document.getElementById('editLocation').value = data.Location;
                document.getElementById('editDescription').value = data.Description || '';
                showModal(editModal);
            }
        } catch(error) {
            showMessage('Error fetching township details', 'error');
        }
    }

    // Field Selector functionality
    document.getElementById('selectAllFields').addEventListener('click', () => {
        document.querySelectorAll('.checkbox-group input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    document.getElementById('deselectAllFields').addEventListener('click', () => {
        document.querySelectorAll('.checkbox-group input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Modal form validation
    document.getElementById('createTownshipForm').addEventListener('submit', (e) => {
        const requiredFields = ['townshipName', 'location'];
        let hasError = false;

        requiredFields.forEach(field => {
            const input = document.getElementById('create_' + field);
            if (!input.value.trim()) {
                input.classList.add('error');
                hasError = true;
            } else {
                input.classList.remove('error');
            }
        });

        if (hasError) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });

    // Initialize
    loadRecords();
});
