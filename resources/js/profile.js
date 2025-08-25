document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById('profile-upload');
    const preview = document.getElementById('profile-image');

    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
