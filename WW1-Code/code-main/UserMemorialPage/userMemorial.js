document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchButton');
    const resetButton = document.getElementById('resetButton');
    const display = document.querySelector('.display');
    const fieldCheckboxes = document.querySelectorAll('input[name="fields"]');
    let currentPage = 1;

    // 字段选择功能
    const selectAllBtn = document.getElementById('selectAllFields');
    const deselectAllBtn = document.getElementById('deselectAllFields');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', () => {
            fieldCheckboxes.forEach(cb => cb.checked = true);
            loadRecords(currentPage);
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', () => {
            fieldCheckboxes.forEach(cb => cb.checked = false);
            document.querySelector('input[value="Surname"]').checked = true;
            loadRecords(currentPage);
        });
    }

    function loadRecords(page = 1) {
        display.innerHTML = '<div class="loading">Loading records...</div>';
        currentPage = page;
        
        const formData = new FormData();
        formData.append('surname', document.getElementById('surname').value.trim());
        formData.append('forename', document.getElementById('forename').value.trim());
        formData.append('regiment', document.getElementById('regiment').value.trim());
        formData.append('page', page);

        const selectedFields = Array.from(fieldCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedFields.length === 0) {
            display.innerHTML = '<p class="error">Please select at least one field to display</p>';
            return;
        }

        formData.append('fields', JSON.stringify(selectedFields));

        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000);

        fetch('userMemorial_connect.php', {
            method: 'POST',
            body: formData,
            signal: controller.signal
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            clearTimeout(timeoutId);
            if (data.success) {
                if (data.records && data.records.length > 0) {
                    displayRecords(data.records);
                    updatePagination(data.page, data.pages);
                    updateResultsInfo(data.total);
                    updateDatabaseStats(data.stats);
                } else {
                    display.innerHTML = '<p class="no-records">No records found matching your criteria.</p>';
                }
            } else {
                throw new Error(data.message || 'Failed to load records');
            }
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Error:', error);
            if (error.name === 'AbortError') {
                display.innerHTML = '<p class="error">Request timed out. Please try again.</p>';
            } else {
                display.innerHTML = `<p class="error">Error loading records: ${error.message}</p>`;
            }
            updatePagination(1, 1);
            updateResultsInfo(0);
        });
    }

    function displayRecords(records) {
        if (!records || records.length === 0) {
            display.innerHTML = '<p class="no-records">No records found.</p>';
            return;
        }

        const table = document.createElement('table');
        table.className = 'records-table';
        
        const selectedFields = Array.from(fieldCheckboxes)
            .filter(cb => cb.checked);
        
        table.innerHTML = `
            <thead>
                <tr>
                    ${selectedFields.map(field => {
                        const titles = {
                            'MemorialID': 'ID',
                            'Cemetery/Memorial': 'Cemetery',
                            'Cemetery/Grave Ref.': 'Grave Ref',
                            'Cemetery / Memorial Country': 'Country',
                            'Memorial Location': 'Location',
                            'Memorial Info': 'Info',
                            'Memorial Postcode': 'Postcode',
                            'Photo available': 'Photo'
                        };
                        return `<th class="col-${field.value.toLowerCase()}">${titles[field.value] || field.value}</th>`;
                    }).join('')}
                </tr>
            </thead>
            <tbody>
                ${records.map((record, index) => `
                    <tr class="${index % 2 === 0 ? 'even-row' : 'odd-row'}">
                        ${selectedFields.map(field => {
                            let value = String(record[field.value] || '-');
                            return `<td class="col-${field.value.toLowerCase()}" title="${escapeHtml(String(record[field.value] || ''))}">
                                ${escapeHtml(value)}
                            </td>`;
                        }).join('')}
                    </tr>
                `).join('')}
            </tbody>
        `;
        
        display.innerHTML = '';
        display.appendChild(table);
    }

    function updatePagination(currentPage, totalPages) {
        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
        document.getElementById('prevPage').disabled = currentPage <= 1;
        document.getElementById('nextPage').disabled = currentPage >= totalPages;
    }

    function updateResultsInfo(total) {
        const resultsInfo = document.getElementById('resultsInfo');
        if (resultsInfo) {
            if (total > 0) {
                resultsInfo.textContent = `Showing page ${currentPage}, Total records: ${total}`;
            } else {
                resultsInfo.textContent = 'No records found';
            }
        }
    }

    function updateDatabaseStats(stats) {
        const statsContainer = document.querySelector('.database-stats');
        if (statsContainer) {
            statsContainer.innerHTML = `
                <p>Total Records: ${stats.total_records}</p>
                <p>Last Update: ${stats.last_update}</p>
                <p>Database Size: ${stats.database_size}</p>
            `;
        }
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    searchButton.addEventListener('click', () => {
        currentPage = 1;
        loadRecords(currentPage);
    });

    resetButton.addEventListener('click', () => {
        document.getElementById('searchForm').reset();
        currentPage = 1;
        loadRecords(currentPage);
    });

    document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            loadRecords(currentPage - 1);
        }
    });

    document.getElementById('nextPage').addEventListener('click', () => {
        loadRecords(currentPage + 1);
    });

    loadRecords(currentPage);
});
