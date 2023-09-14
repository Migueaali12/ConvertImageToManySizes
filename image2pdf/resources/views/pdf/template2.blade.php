<!DOCTYPE html>
<html>

<head>
    <title>Converted Image PDF</title>
    <style>
        @page {
            margin-left: 0.1cm;
            margin-right: 0.1cm;
            margin-top: 0.1cm;
            margin-bottom: 0.1cm;
        }

        img {
            max-width: 100%;
            margin-left: 5px;
            margin-right: 5px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .image-item {
            flex: 0 0 calc(50% - 10px);
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="image-container">
        @foreach ($images as $image)
            <div class="image-item">
                <img src="{{ $image->encode('data-url') }}" alt="ConversionPDF">
            </div>
        @endforeach
    </div>
</body>

</html>
