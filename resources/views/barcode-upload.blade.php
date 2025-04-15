<!DOCTYPE html>
<html>
<head>
    <title>Barcode Reader</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">Upload Barcode Image</h4>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('barcode.read') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="barcode_image" class="form-label">Select Image</label>
                            <input type="file" class="form-control" name="barcode_image" id="barcode_image" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Read Barcode</button>
                    </form>

                    @isset($barcodeText)
                        <hr>
                        <h5>Scanned Barcode:</h5>
                        <div class="alert alert-info">{{ $barcodeText }}</div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
