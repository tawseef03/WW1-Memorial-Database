document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchButton');
    const resetButton = document.getElementById('resetButton');
    const display = document.querySelector('.display');
    const fieldCheckboxes = document.querySelectorAll('input[name="fields"]');
    let currentPage = 1;

    // 添加字段选择功能
    const selectAllBtn = document.getElementById('selectAllFields');
    const deselectAllBtn = document.getElementById('deselectAllFields');
    
    // 添加按钮事件监听器
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
        currentPage = page;  // 更新当前页码
        
        const formData = new FormData();
        formData.append('surname', document.getElementById('surname').value.trim());
        formData.append('forename', document.getElementById('forename').value.trim());
        formData.append('newspaper', document.getElementById('newspaper').value.trim());
        formData.append('page', page);

        // 获取选中的字段并添加到请求中
        const selectedFields = Array.from(fieldCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedFields.length === 0) {
            display.innerHTML = '<p class="error">Please select at least one field to display</p>';
            return;
        }

        formData.append('fields', JSON.stringify(selectedFields));

        // 设置超时
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30秒超时

        fetch('newspaper_connect.php', {
            method: 'POST',
            body: formData,
            signal: controller.signal
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            clearTimeout(timeoutId);
            if (data.success) {
                if (data.records && data.records.length > 0) {
                    displayRecords(data.records);
                    updatePagination(data.page, data.pages);
                    updateResultsInfo(data.total);
                    updateDatabaseStats(data.database_stats);
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
            // 重置分页和统计信息
            updatePagination(1, 1);
            updateResultsInfo(0);
        });
    }

    // 更新结果信息显示
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

    // 修改 updateDatabaseStats 函数
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

    // 添加 HTML 转义函数
    function escapeHtml(text) {
        if (text === null || text === undefined) {
            return '';
        }
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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
                            'NewspaperID': 'ID',
                            'ArticleDescription': 'Article',
                            'NewspaperName': 'Newspaper',
                            'PaperDate': 'Date',
                            'PageCol': 'Page/Col',
                            'PhotoIncl': 'Photo'
                        };
                        return `<th class="col-${field.value.toLowerCase()}">${titles[field.value] || field.value}</th>`;
                    }).join('')}
                </tr>
            </thead>
            <tbody>
                ${records.map(record => `
                    <tr>
                        ${selectedFields.map(field => {
                            let value = record[field.value] || '-';
                            if (field.value === 'PhotoIncl') {
                                value = value === 'Yes' ? '✓' : '✗';
                            }
                            return `<td class="col-${field.value.toLowerCase()}" title="${escapeHtml(record[field.value] || '')}">
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
});
