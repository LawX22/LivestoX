function openUpdateModal(type) {
    const modalTitle = type === 'profile' ? 'Update Profile Picture' : 'Update Cover Picture';
    const uploadInputId = type === 'profile' ? 'profilePictureInput' : 'coverPictureInput';
    const modalContent = `
        <div class="modal">
            <div class="modal-content">
                <h2>${modalTitle}</h2>
                <form id="${type}UpdateForm" enctype="multipart/form-data">
                    <input type="file" id="${uploadInputId}" name="file" accept="image/*" required>
                    <button type="submit">Upload</button>
                    <button type="button" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalContent);

    const form = document.getElementById(`${type}UpdateForm`);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        uploadImage(type);
    });
}

function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) modal.remove();
}

function uploadImage(type) {
    const formData = new FormData();
    const fileInput = document.getElementById(
        type === 'profile' ? 'profilePictureInput' : 'coverPictureInput'
    );

    if (fileInput && fileInput.files.length > 0) {
        formData.append('image', fileInput.files[0]);
        formData.append('type', type);

        fetch('../../Backend/user-profile/upload_picture.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(`${type.charAt(0).toUpperCase() + type.slice(1)} picture updated successfully.`);
                    window.location.reload(); // Reload to reflect the updated picture
                } else {
                    alert('Error updating picture: ' + data.message);
                }
            })
            .catch((error) => {
                alert('Error: ' + error.message);
            })
            .finally(() => {
                closeModal();
            });
    } else {
        alert('Please select an image to upload.');
    }
}
