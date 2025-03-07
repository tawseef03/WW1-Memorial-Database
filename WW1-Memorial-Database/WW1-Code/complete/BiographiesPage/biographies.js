document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    const recordsPerPage = 10;
    let totalRecords = 0;
    
    const searchBtn = document.getElementById('searchBtn');
    const resetBtn = document.getElementById('resetBtn');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');
    const resultsTable = document.getElementById('resultsTable');

    // 初始化加载数据
    loadBiographies();

    // 添加事件监听器
    searchBtn.addEventListener('click', performSearch);
    resetBtn.addEventListener('click', resetSearch);
    prevPageBtn.addEventListener('click', () => changePage(-1));
    nextPageBtn.addEventListener('click', () => changePage(1));

    function loadBiographies(searchParams = {}) {
        // TODO: 实现从后端API加载数据
        fetch('../api/biographies.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                page: currentPage,
                limit: recordsPerPage,
                ...searchParams
            })
        })
        .then(response => response.json())
        .then(data => {
            displayResults(data.records);
            updatePagination(data.total);
        })
        .catch(error => {
            console.error('Error loading data:', error);
            resultsTable.innerHTML = '<p>Error loading data. Please try again later.</p>';
        });
    }

    function performSearch() {
        const nameSearch = document.getElementById('nameSearch').value.trim();
        const regimentSearch = document.getElementById('regimentSearch').value.trim();
        
        currentPage = 1;
        loadBiographies({
            name: nameSearch,
            regiment: regimentSearch
        });
    }

    function resetSearch() {
        document.getElementById('nameSearch').value = '';
        document.getElementById('regimentSearch').value = '';
        currentPage = 1;
        loadBiographies();
    }

    function changePage(delta) {
        currentPage += delta;
        loadBiographies();
    }

    function updatePagination(total) {
        totalRecords = total;
        const totalPages = Math.ceil(total / recordsPerPage);
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;
    }

    function displayResults(records) {
        if (!records.length) {
            resultsTable.innerHTML = '<p>No results found.</p>';
            return;
        }

        const table = document.createElement('table');
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Regiment</th>
                    <th>Service Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ${records.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.regiment}</td>
                        <td>${record.serviceNumber}</td>
                        <td>
                            <button onclick="viewDetails(${record.id})">View</button>
                            <button onclick="editRecord(${record.id})">Edit</button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        `;
        
        resultsTable.innerHTML = '';
        resultsTable.appendChild(table);
    }
});

// 全局函数用于处理记录操作
function viewDetails(id) {
    window.location.href = `biography-details.html?id=${id}`;
}

function editRecord(id) {
    window.location.href = `edit-biography.html?id=${id}`;
}
