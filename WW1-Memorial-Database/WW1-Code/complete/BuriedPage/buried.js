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
    const resultsInfo = document.getElementById('resultsInfo');

    // 初始化加载数据
    loadBurialRecords();

    // 添加事件监听器
    searchBtn.addEventListener('click', performSearch);
    resetBtn.addEventListener('click', resetSearch);
    prevPageBtn.addEventListener('click', () => changePage(-1));
    nextPageBtn.addEventListener('click', () => changePage(1));

    function loadBurialRecords(searchParams = {}) {
        fetch('../api/buried.php', {
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
            updateResultsInfo(data.total);
        })
        .catch(error => {
            console.error('Error loading data:', error);
            resultsTable.innerHTML = '<p>Error loading data. Please try again later.</p>';
        });
    }

    function performSearch() {
        const surname = document.getElementById('surnameSearch').value.trim();
        const cemetery = document.getElementById('cemeterySearch').value.trim();
        
        currentPage = 1;
        loadBurialRecords({
            surname: surname,
            cemetery: cemetery
        });
    }

    function resetSearch() {
        document.getElementById('surnameSearch').value = '';
        document.getElementById('cemeterySearch').value = '';
        currentPage = 1;
        loadBurialRecords();
    }

    function updateResultsInfo(total) {
        resultsInfo.textContent = `Found ${total} record(s)`;
    }

    function changePage(delta) {
        currentPage += delta;
        loadBurialRecords();
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
            resultsTable.innerHTML = '<p>No records found.</p>';
            return;
        }

        const table = document.createElement('table');
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Surname</th>
                    <th>Forename</th>
                    <th>Cemetery</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ${records.map(record => `
                    <tr>
                        <td>${record.surname || ''}</td>
                        <td>${record.forename || ''}</td>
                        <td>${record.cemetery || ''}</td>
                        <td>${record.location || ''}</td>
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
    window.location.href = `burial-details.html?id=${id}`;
}

function editRecord(id) {
    window.location.href = `edit-burial.html?id=${id}`;
}
