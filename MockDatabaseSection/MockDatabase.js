document.addEventListener('DOMContentLoaded', function() {
    // Sample database records 
    

    const recordsPerPage = 5;
    let currentPage = 1;
    let filteredRecords = [...databaseRecords];
    
    
    const searchButton = document.getElementById('searchButton');
    const resetButton = document.getElementById('resetButton');
    const recordsBody = document.getElementById('recordsBody');
    const resultsHeading = document.getElementById('resultsHeading');
    const resultsInfo = document.getElementById('resultsInfo');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');
    const advancedSearchButton = document.getElementById('advancedSearchButton');
    const modal = document.getElementById('recordModal');
    const modalContent = document.getElementById('modalContent');
    const closeButton = document.querySelector('.close-button');
    
   
    displayRecords();
    
    
    searchButton.addEventListener('click', performSearch);
    resetButton.addEventListener('click', resetSearch);
    prevPageButton.addEventListener('click', goToPrevPage);
    nextPageButton.addEventListener('click', goToNextPage);
    advancedSearchButton.addEventListener('click', toggleAdvancedSearch);
    closeButton.addEventListener('click', closeModal);
    
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    
    function performSearch() {
        const surname = document.getElementById('surname').value.trim().toLowerCase();
        const forename = document.getElementById('forename').value.trim().toLowerCase();
        const regiment = document.getElementById('regiment').value.trim().toLowerCase();
        const serviceNumber = document.getElementById('serviceNumber').value.trim().toLowerCase();
        
        filteredRecords = databaseRecords.filter(record => {
            let match = true;
            
            if (surname && !record.surname.toLowerCase().includes(surname)) {
                match = false;
            }
            
            if (forename && !record.forename.toLowerCase().includes(forename)) {
                match = false;
            }
            
            if (regiment && !record.regiment.toLowerCase().includes(regiment)) {
                match = false;
            }
            
            if (serviceNumber && !record.serviceNumber.toLowerCase().includes(serviceNumber)) {
                match = false;
            }
            
            return match;
        });
        
        currentPage = 1;
        displayRecords();
        updateResultsInfo();
    }
    
    
    function resetSearch() {
        document.getElementById('searchForm').reset();
        filteredRecords = [...databaseRecords];
        currentPage = 1;
        displayRecords();
        updateResultsInfo();
    }
    
    
    function displayRecords() {
        recordsBody.innerHTML = '';
        
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, filteredRecords.length);
        const recordsToDisplay = filteredRecords.slice(startIndex, endIndex);
        
        if (recordsToDisplay.length === 0) {
            recordsBody.innerHTML = `<tr><td colspan="7" class="no-records">No records found matching your search criteria.</td></tr>`;
        } else {
            recordsToDisplay.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.surname}</td>
                    <td>${record.forename}</td>
                    <td>${record.regiment}</td>
                    <td>${record.serviceNumber}</td>
                    <td>${record.birthYear}</td>
                    <td>${record.deathDate || 'Unknown'}</td>
                    <td><button class="details-button" data-id="${record.id}">View Details</button></td>
                `;
                recordsBody.appendChild(row);
            });
            
            
            document.querySelectorAll('.details-button').forEach(button => {
                button.addEventListener('click', function() {
                    const recordId = parseInt(this.getAttribute('data-id'));
                    showRecordDetails(recordId);
                });
            });
        }
        
        updatePagination();
    }
    
    
    function updateResultsInfo() {
        if (filteredRecords.length === databaseRecords.length) {
            resultsInfo.textContent = `Showing all ${filteredRecords.length} records.`;
            resultsHeading.textContent = 'Records Display';
        } else {
            resultsInfo.textContent = `Found ${filteredRecords.length} record(s) matching your search criteria.`;
            resultsHeading.textContent = 'Search Results';
        }
    }
    
   
    function updatePagination() {
        const totalPages = Math.ceil(filteredRecords.length / recordsPerPage);
        
        pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
        
        prevPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage >= totalPages;
    }
    
   
    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayRecords();
        }
    }
    
    function goToNextPage() {
        const totalPages = Math.ceil(filteredRecords.length / recordsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayRecords();
        }
    }
    
    
    function toggleAdvancedSearch() {
        alert('Advanced search options will be implemented in a future update.');
    }
    
    
    function showRecordDetails(recordId) {
        const record = databaseRecords.find(r => r.id === recordId);
        
        if (record) {
            modalContent.innerHTML = `
                <div class="record-details">
                    <h3>${record.rank} ${record.forename} ${record.surname}</h3>
                    <p><strong>Service Number:</strong> ${record.serviceNumber}</p>
                    <p><strong>Regiment:</strong> ${record.regiment}</p>
                    <p><strong>Born:</strong> ${record.birthYear} in ${record.hometown}</p>
                    <p><strong>Death:</strong> ${record.deathDate ? record.deathDate + ' at ' + record.placeOfDeath : 'Not recorded'}</p>
                    <p><strong>Cemetery/Memorial:</strong> ${record.cemetery || 'Not recorded'}</p>
                    <p><strong>Additional Information:</strong></p>
                    <p>${record.notes || 'No additional information available.'}</p>
                </div>
            `;
            
            modal.style.display = 'block';
        }
    }
    
   
    function closeModal() {
        modal.style.display = 'none';
    }
});
