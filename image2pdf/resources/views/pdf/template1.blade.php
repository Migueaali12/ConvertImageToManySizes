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

        body {
            margin-top: 2cm;
            margin-bottom: 2cm;
        }

        img {
            max-width: 100%;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            margin-top: 0.6cm;
            margin-bottom: 0.6cm;
        }

        .image-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50%, 1fr));
            gap: 5px;
        }
    </style>
</head>

<bod id="bodyImage">
    <div class="image-container">
        @foreach ($images as $image)
        <img src="{{ $image->encode('data-url') }}" alt="ConversionPDF">
        @endforeach
    </div>
</body>

</html>