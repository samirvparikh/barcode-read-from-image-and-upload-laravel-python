<!DOCTYPE html>
<html>
<head>
    <title>Upload & Barcode</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Upload Image with Barcode</h2>
    
    <form id="uploadForm">
        <input type="file" name="image" id="image" />
        <input type="text" name="barcode" id="barcode" placeholder="Enter barcode" />
        <button type="submit">Upload</button>
        <p id="status"></p>
    </form>
    <button id="processBtn">Run Barcode Processing</button>

    <script>
    document.getElementById('uploadForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const image = document.getElementById('image').files[0];
        const barcodeValue = document.getElementById('barcode').value;

        let formData = new FormData();
        formData.append('image', image);
        formData.append('barcode', barcodeValue);

        fetch('/api/upload', {
            method: 'POST',
            body: formData,
        })
        .then(async response => {
            const text = await response.text();
            try {
                const data = JSON.parse(text);
                console.log('✅ Success:', data);
                // console.log('Barcode value:', result.text);
                document.getElementById('barcode').value = data.barcode;
                document.getElementById("status").innerText = "Uploaded: " + data.status;
            } catch (err) {
                console.error('❌ Server did not return JSON:');
                console.error(text); // Show the HTML error
            }
        })
        .catch(error => console.error('❌ Fetch Error:', error));

    });

    document.getElementById('processBtn').addEventListener('click', () => {
        fetch('run-barcode-processing')
            .then(res => res.json())
            .then(data => {
                document.getElementById('status').innerText = data.message;
                console.log('✅', data);
            })
            .catch(err => {
                document.getElementById('status').innerText = 'Something went wrong!';
                console.error('❌ Error:', err);
            });
    });
    </script>


</body>

</html>
