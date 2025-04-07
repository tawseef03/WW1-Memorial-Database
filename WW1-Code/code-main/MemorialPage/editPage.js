document.addEventListener('DOMContentLoaded', function() {
    const display = document.querySelector('.display');

    function loadRecords() {
        display.innerHTML = '<div class="loading">Loading records...</div>';

        fetch('memorial_connect.php', {
            method: 'POST',
            body: new URLSearchParams({ action: 'fetch' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRecords(data.records);
            } else {
                display.innerHTML = `<p class="error">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            display.innerHTML = '<p class="error">Error loading records</p>';
        });
    }

    function displayRecords(records) {
        if (!records || records.length === 0) {
            display.innerHTML = '<p class="no-records">No records found.</p>';
            return;
        }

        const table = document.createElement('table');
        table.className = 'records-table';

        table.innerHTML = `
            <thead>
                <tr>
                    <th>Surname</th>
                    <th>Forename</th>
                    <th>Regiment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ${records.map(record => `
                    <tr data-id="${record.MemorialID}">
                        <td contenteditable="true">${record.Surname}</td>
                        <td contenteditable="true">${record.Forename}</td>
                        <td contenteditable="true">${record.Regiment}</td>
                        <td><button class="save-button">Save</button></td>
                    </tr>
                `).join('')}
            </tbody>
        `;

        display.innerHTML = '';
        display.appendChild(table);

        document.querySelectorAll('.save-button').forEach(button => {
            button.addEventListener('click', saveRecord);
        });
    }

    function saveRecord(event) {
        const row = event.target.closest('tr');
        const id = row.dataset.id;
        const surname = row.cells[0].textContent;
        const forename = row.cells[1].textContent;
        const regiment = row.cells[2].textContent;

        fetch('memorial_connect.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'update',
                id: id,
                surname: surname,
                forename: forename,
                regiment: regiment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Record updated successfully');
            } else {
                alert('Error updating record: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating record');
        });
    }

    loadRecords();
});