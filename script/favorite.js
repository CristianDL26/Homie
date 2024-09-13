/* window.handleFavoriteChange = function(checkbox) {
    const pro_id = document.getElementById('pro_id');
    const isChecked = checkbox.checked;
    if (itemId) {
        fetch('add_to_favorites.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ item_id: itemId, checked: isChecked })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                console.error('Error updating favorites:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const favoriteCheckbox = document.getElementById('favoriteCheckbox');
    favoriteCheckbox.addEventListener('change', function() {
        handleFavoriteChange(this);
    });
}); */