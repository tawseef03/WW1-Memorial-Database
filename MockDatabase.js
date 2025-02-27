document.addEventListener('DOMContentLoaded', function() {
    // Sample database records - this would normally come from a backend server
    const databaseRecords = [
        {
            id: 1, 
            surname: "Smith", 
            forename: "John", 
            regiment: "West Yorkshire Regiment", 
            serviceNumber: "12345", 
            birthYear: 1890, 
            deathDate: "12/10/1916",
            rank: "Private",
            hometown: "Bradford",
            placeOfDeath: "Somme, France",
            cemetery: "Thiepval Memorial",
            notes: "Killed in action during the Battle of the Somme. Left behind a wife and two children."
        },
        {
            id: 2, 
            surname: "Wilson", 
            forename: "Thomas", 
            regiment: "Duke of Wellington's Regiment", 
            serviceNumber: "23456", 
            birthYear: 1885, 
            deathDate: "25/04/1918",
            rank: "Corporal",
            hometown: "Leeds",
            placeOfDeath: "Ypres, Belgium",
            cemetery: "Tyne Cot Cemetery",
            notes: "Awarded the Military Medal for bravery in the field."
        },
        {
            id: 3, 
            surname: "Brown", 
            forename: "William", 
            regiment: "Yorkshire Hussars", 
            serviceNumber: "34567", 
            birthYear: 1893, 
            deathDate: "03/06/1917",
            rank: "Lance Corporal",
            hometown: "Bradford",
            placeOfDeath: "Arras, France",
            cemetery: "Arras Memorial",
            notes: "Previously wounded at Loos in 1915. Returned to service in 1916."
        },
        {
            id: 4, 
            surname: "Johnson", 
            forename: "Arthur", 
            regiment: "West Yorkshire Regiment", 
            serviceNumber: "45678", 
            birthYear: 1888, 
            deathDate: "15/09/1916",
            rank: "Sergeant",
            hometown: "Halifax",
            placeOfDeath: "Somme, France",
            cemetery: "Thiepval Memorial",
            notes: "Served since 1914. Mentioned in dispatches for gallantry."
        },
        {
            id: 5, 
            surname: "Taylor", 
            forename: "Edward", 
            regiment: "Royal Field Artillery", 
            serviceNumber: "56789", 
            birthYear: 1891, 
            deathDate: "12/08/1917",
            rank: "Gunner",
            hometown: "Bradford",
            placeOfDeath: "Passchendaele, Belgium",
            cemetery: "Tyne Cot Cemetery",
            notes: "Former mill worker. Survived by parents and three siblings."
        },
        {
            id: 6, 
            surname: "Davis", 
            forename: "Henry", 
            regiment: "East Yorkshire Regiment", 
            serviceNumber: "67890", 
            birthYear: 1895, 
            deathDate: "09/04/1918",
            rank: "Private",
            hometown: "Shipley",
            placeOfDeath: "Lys, France",
            cemetery: "Ploegsteert Memorial",
            notes: "One of three brothers who served. The only one who did not return."
        },
        {
            id: 7, 
            surname: "Wilson", 
            forename: "George", 
            regiment: "Duke of Wellington's Regiment", 
            serviceNumber: "78901", 
            birthYear: 1887, 
            deathDate: "",
            rank: "Private",
            hometown: "Bradford",
            placeOfDeath: "",
            cemetery: "",
            notes: "Wounded at Gallipoli in 1915. Discharged due to wounds in 1916. Died in 1925 from war-related injuries."
        },
        {
            id: 8, 
            surname: "Evans", 
            forename: "Albert", 
            regiment: "Royal Army Medical Corps", 
            serviceNumber: "89012", 
            birthYear: 1889, 
            deathDate: "23/07/1916",
            rank: "Private",
            hometown: "Keighley",
            placeOfDeath: "Somme, France",
            cemetery: "Etaples Military Cemetery",
            notes: "Former doctor's assistant. Died of wounds received at the Somme."
        },
        {
            id: 9, 
            surname: "Smith", 
            forename: "Charles", 
            regiment: "Yorkshire Regiment", 
            serviceNumber: "90123", 
            birthYear: 1894, 
            deathDate: "31/07/1917",
            rank: "Private",
            hometown: "Bradford",
            placeOfDeath: "Ypres, Belgium",
            cemetery: "Menin Gate Memorial",
            notes: "Former textile worker. Enlisted in 1915."
        },
        {
            id: 10, 
            surname: "Roberts", 
            forename: "James", 
            regiment: "King's Own Yorkshire Light Infantry", 
            serviceNumber: "10234", 
            birthYear: 1892, 
            deathDate: "04/11/1918",
            rank: "Lance Sergeant",
            hometown: "Bingley",
            placeOfDeath: "Le Quesnoy, France",
            cemetery: "Le Quesnoy Communal Cemetery Extension",
            notes: "Died just one week before the Armistice. Had served since 1914."
        }
    ];
    
    const recordsPerPage = 5;
    let currentPage = 1;
    let filteredRecords = [...databaseRecords];
    
    // DOM elements
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
    
    // Initialize the page
    displayRecords();
    
    // Event listeners
    searchButton.addEventListener('click', performSearch);
    resetButton.addEventListener('click', resetSearch);
    prevPageButton.addEventListener('click', goToPrevPage);
    nextPageButton.addEventListener('click', goToNextPage);
    advancedSearchButton.addEventListener('click', toggleAdvancedSearch);
    closeButton.addEventListener('click', closeModal);
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Search function
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
    
    // Reset search function
    function resetSearch() {
        document.getElementById('searchForm').reset();
        filteredRecords = [...databaseRecords];
        currentPage = 1;
        displayRecords();
        updateResultsInfo();
    }
    
    // Display records function
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
            
            // Add event listeners to details buttons
            document.querySelectorAll('.details-button').forEach(button => {
                button.addEventListener('click', function() {
                    const recordId = parseInt(this.getAttribute('data-id'));
                    showRecordDetails(recordId);
                });
            });
        }
        
        updatePagination();
    }
    
    // Update results info
    function updateResultsInfo() {
        if (filteredRecords.length === databaseRecords.length) {
            resultsInfo.textContent = `Showing all ${filteredRecords.length} records.`;
            resultsHeading.textContent = 'Records Display';
        } else {
            resultsInfo.textContent = `Found ${filteredRecords.length} record(s) matching your search criteria.`;
            resultsHeading.textContent = 'Search Results';
        }
    }
    
    // Update pagination controls
    function updatePagination() {
        const totalPages = Math.ceil(filteredRecords.length / recordsPerPage);
        
        pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
        
        prevPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage >= totalPages;
    }
    
    // Pagination functions
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
    
    // Advanced search toggle (placeholder for now)
    function toggleAdvancedSearch() {
        alert('Advanced search options will be implemented in a future update.');
    }
    
    // Show record details in modal
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
    
    // Close modal
    function closeModal() {
        modal.style.display = 'none';
    }
});
