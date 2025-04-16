function upload() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
        alert("Please select a file.");
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        // Placeholder for barcode reading
        let barcode = "DEMO123456"; // Replace with actual barcode scanner logic

        const formData = new FormData();
        formData.append("image", file);
        formData.append("barcode", barcode);

        fetch('/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(res => res.json())
          .then(data => {
              document.getElementById("status").innerText = "Uploaded: " + data.barcode;
          });
    };
    reader.readAsDataURL(file);
}
