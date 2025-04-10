<!-- Create Township Modal -->
<div class="modal" id="createModal">
    <div class="modal-content">
        <button class="close-btn" id="closeCreateModal">&times;</button>
        <h2>Add New Township</h2>
        <form id="createTownshipForm" method="POST" class="modal-form">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group required">
                <label for="create_townshipName">Township Name:</label>
                <input type="text" id="create_townshipName" name="townshipName" required>
                <span class="required-mark">*</span>
            </div>
            
            <div class="form-group required">
                <label for="create_location">Location:</label>
                <input type="text" id="create_location" name="location" required>
                <span class="required-mark">*</span>
            </div>
            
            <div class="form-group">
                <label for="create_description">Description:</label>
                <textarea id="create_description" name="description" rows="4"></textarea>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="submit-btn">Create Township</button>
                <button type="button" class="cancel-btn" id="cancelCreate">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Township Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <button class="close-btn" id="closeEditModal">&times;</button>
        <h2>Edit Township</h2>
        <form id="editTownshipForm" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_record_id" name="record_id">
            <div class="form-group">
                <label for="edit_townshipName">Township Name:*</label>
                <input type="text" id="edit_townshipName" name="townshipName" required>
            </div>
            <div class="form-group">
                <label for="edit_location">Location:*</label>
                <input type="text" id="edit_location" name="location" required>
            </div>
            <div class="form-group">
                <label for="edit_description">Description:</label>
                <textarea id="edit_description" name="description" rows="4"></textarea>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-btn">Update Township</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <button class="close-btn" id="closeDeleteModal">&times;</button>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this township record? This action cannot be undone.</p>
        <form id="deleteTownshipForm" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="delete_record_id" name="record_id">
            <div class="form-buttons">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="delete-btn">Delete</button>
            </div>
        </form>
    </div>
</div>

<div class="overlay" id="modalOverlay"></div>
