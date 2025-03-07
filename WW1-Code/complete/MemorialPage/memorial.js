document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchButton');
    const resetButton = document.getElementById('resetButton');
    const display = document.querySelector('.display');
    let currentPage = 1;

    // 添加字段选择功能
    const selectAllBtn = document.getElementById('selectAllFields');
    const deselectAllBtn = document.getElementById('deselectAllFields');
    const fieldCheckboxes = document.querySelectorAll('input[name="fields"]');
    
    selectAllBtn.addEventListener('click', () => {
        fieldCheckboxes.forEach(cb => cb.checked = true);
        loadRecords(currentPage);
    });
    
    deselectAllBtn.addEventListener('click', () => {
        fieldCheckboxes.forEach(cb => cb.checked = false);
        // 确保至少选中一个字段
        document.querySelector('input[value="Surname"]').checked = true;
        loadRecords(currentPage);
    });

    function loadRecords(page = 1) {
        display.innerHTML = '<div class="loading">Loading records...</div>';
        
        const formData = new FormData();
        formData.append('surname', document.getElementById('surname').value);
        formData.append('forename', document.getElementById('forename').value);
        formData.append('regiment', document.getElementById('regiment').value);
        formData.append('page', page);

        // 添加选中的字段
        const selectedFields = Array.from(fieldCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        formData.append('fields', JSON.stringify(selectedFields));

        // 更新fetch URL以使用相对路径
        fetch('memorial_connect.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRecords(data.records);
                updatePagination(data.page, data.pages);
                updateResultsInfo(data.total);
                // 添加显示数据库统计信息
                updateDatabaseStats(data.database_stats);
            } else {
                display.innerHTML = `<p class="error">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            display.innerHTML = '<p class="error">Error loading records</p>';
        });
    }

    // 添加更新数据库统计信息的函数
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

    function displayRecords(records) {
        if (!records || records.length === 0) {
            display.innerHTML = '<p class="no-records">No records found.</p>';
            return;
        }

        const table = document.createElement('table');
        table.className = 'records-table';

        // 动态生成表头
        const selectedFields = Array.from(fieldCheckboxes)
            .filter(cb => cb.checked);
        
        table.innerHTML = `
            <thead>
                <tr>
                    ${selectedFields.map(field => 
                        `<th class="col-${field.value.toLowerCase()}">${field.value}</th>`
                    ).join('')}
                </tr>
            </thead>
            <tbody>
                ${records.map(record => `
                    <tr>
                        ${selectedFields.map(field => 
                            `<td class="col-${field.value.toLowerCase()}">
                                ${escapeHtml(record[field.value] || '-')}
                            </td>`
                        ).join('')}
                    </tr>
                `).join('')}
            </tbody>
        `;
        
        display.innerHTML = '';
        display.appendChild(table);
    }

    // 添加HTML转义函数
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // 更新结果信息显示
    function updateResultsInfo(total) {
        const resultsInfo = document.getElementById('resultsInfo');
        if (resultsInfo) {
            resultsInfo.textContent = `Showing page ${currentPage} of ${Math.ceil(total/20)}, Total records: ${total}`;
        }
    }

    function updatePagination(currentPage, totalPages) {
        const pageInfo = document.getElementById('pageInfo');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        
        if (pageInfo) pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        if (prevPage) prevPage.disabled = currentPage <= 1;
        if (nextPage) nextPage.disabled = currentPage >= totalPages;

        // 添加分页按钮事件监听
        if (prevPage) {
            prevPage.onclick = () => loadRecords(currentPage - 1);
        }
        if (nextPage) {
            nextPage.onclick = () => loadRecords(currentPage + 1);
        }
    }

    // 事件监听器设置
    searchButton.addEventListener('click', () => {
        currentPage = 1;
        loadRecords(currentPage);
    });

    resetButton.addEventListener('click', () => {
        document.getElementById('searchForm').reset();
        currentPage = 1;
        loadRecords(currentPage);
    });

    // 初始加载
    loadRecords(currentPage);

    // ...其他辅助函数(displayRecords, updatePagination等)...
});
