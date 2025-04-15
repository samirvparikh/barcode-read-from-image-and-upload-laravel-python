<!DOCTYPE html>
<html>
<head>
    <title>Upload & Barcode</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Upload Image with Barcode</h2>
    
    <form id="uploadForm">
        <input type="file" id="image" name="image" />
        <input type="text" id="barcode" name="barcode" readonly />

        <button type="submit">Upload</button>
    </form>

    <script type="module">
    import { BrowserMultiFormatReader } from 'https://cdn.jsdelivr.net/npm/@zxing/browser@latest/+esm';

    const codeReader = new BrowserMultiFormatReader();

    document.getElementById('image').addEventListener('change', async function (e) {
        const file = e.target.files[0];
        const imageUrl = URL.createObjectURL(file);

        const img = document.createElement('img');
        img.src = imageUrl;
        img.onload = async () => {
        try {
            const result = await codeReader.decodeFromImageElement(img);
            console.log('Barcode value:', result.text);
            document.getElementById('barcode').value = result.text;
        } catch (err) {
            console.error('Barcode not detected:', err);
        }
        };
    });
    </script>



</body>

</html>





<!DOCTYPE html>
<html>
<head>
    <title>Upload & Barcode</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Upload Image with Barcode</h2>
    
    <form id="uploadForm">
        <input type="file" name="image" id="image" required />
        <input type="text" name="barcode" id="barcode" placeholder="Enter barcode" required />
        <button type="submit">Upload</button>
    </form>

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
        })
        .then(async response => {
            const text = await response.text();
            try {
                const data = JSON.parse(text);
                console.log('✅ Success:', data);
            } catch (err) {
                console.error('❌ Server did not return JSON:');
                console.error(text); // Show the HTML error
            }
        })
        .catch(error => console.error('❌ Fetch Error:', error));

    
    </script>


</body>

</html>
