<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Image2Pdf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>

    <script>
        const uploadImageUrl = @php echo json_encode(route('uploadImage')); @endphp;
    </script>

    <script>
        const convertToPdfUrl = @php echo json_encode(route('convertToPdf')); @endphp;
    </script>

    @vite(['resources/js/frontScript.js'])

    <style>
        #successAlert {
            display: none;
        }

        #uploadedImgs {
            display: none;
        }

        #sizeBtnsContainer {
            display: none;
        }

        #conversionTypeContainer {
            display: none;
        }

        #convertBtnContainer {
            display: none;
        }

        #addSizeBtn {
            margin-top: 4px;
            margin-bottom: 4px;
            margin-right: 13px;
            background-color: rgb(113, 37, 255);
            border: 1px solid rgba(228, 220, 255, 0.4);
            color: rgb(255, 255, 255);
        }

        .image-btn {
            margin-right: 8px;
            margin-top: 8px;
            position: relative;
        }

        .image-btn.active {
            background-color: rgb(252, 94, 32);
            border: 1px solid rgba(255, 229, 220, 0.4);
            color: rgb(255, 255, 255);
        }

        .image-btn:not(.active) {
            background-color: rgb(255, 239, 220);
            border: 1px solid rgba(240, 125, 80, 0.4);
            color: rgb(10, 10, 10);
        }

        .custom-size-btn-delete {
            position: absolute;
            top: -7px;
            right: -7px;
            background-color: rgb(255, 72, 72);
            color: white;
            padding: 2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .custom-size-btn {
            margin-right: 13px;
            margin-top: 9px;
            margin-bottom: 5px;
            position: relative;
        }

        .default-size-btn {
            margin-right: 9px;
            margin-top: 9px;
            margin-bottom: 5px;
            position: relative;

        }

        .size-btn.active {
            background-color: rgb(113, 37, 255);
            border: 1px solid rgba(228, 220, 255, 0.4);
            color: rgb(255, 255, 255);
        }

        .size-btn:not(.active) {
            background-color: rgb(229, 220, 255);
            border: 1px solid rgba(120, 80, 240, 0.4);
            color: rgb(10, 10, 10);
        }

        .convert-type-btn {
            margin-right: 8px;
            margin-top: 8px;
            position: relative;
        }

        .convert-type-btn.active {
            background-color: rgb(41, 212, 149);
            border: 1px solid rgba(220, 255, 245, 0.4);
            color: rgb(255, 255, 255);
        }

        .convert-type-btn:not(.active) {
            background-color: rgb(189, 255, 226);
            border: 1px solid rgba(80, 240, 179, 0.4);
            color: rgb(10, 10, 10);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Convert Image To Many Sizes</h1>

        <form id="imageUploadForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">Sube una imagen (PNG o JPG):</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/png,image/jpeg"
                    required>
            </div>
            <button type="button" class="btn btn-primary" id="uploadBtn">Subir Imagen</button>
        </form>

    </div>

    <div class="mt-4 container" id="successAlert"></div>

    <div class="img-btn-container">
        <div class="mt-2 container" id="uploadedImgs">
            <h3 class="mb-3">Imagenes:</h3>
        </div>
    </div>

    <div class="size-btns-container" id="sizeBtnsContainer">
        <div class="mt-4 container">
            <h3 class="mb-3">Tamaños:</h3>
            <input type="hidden" name="imageSize" id="imgSizeInput" value="">
            <div class="size-button-container mb-2 mt-2">

                @if (isset($sizes))
                    @foreach ($sizes as $size)
                        @php
                            [$alto, $ancho] = explode('x', $size);
                            $buttonText = "{$alto}cm x {$ancho}cm";
                        @endphp
                        <button type="button" class="btn btn-primary size-btn default-size-btn active"
                            data-size="{{ $size }}">{{ $buttonText }}
                        </button>
                    @endforeach
                @endif

                <button type="button" class="btn btn-primary size-btn default-size-btn" data-size="10.5x6.3">10.5cm x
                    6.3cm</button>

                <button type="button" class="btn btn-primary size-btn default-size-btn" data-size="7.2x6.3">7.2cm x
                    6.3cm</button>

            </div>

            <div class="custom-size-button-container mb-3 me-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSizeModal"
                    id="addSizeBtn">+</button>
            </div>
        </div>
    </div>

    <div class="convert-type-btn-container">
        <div class="mt-2 container" id="conversionTypeContainer">
            <h3 class="mb-3">Conversión:</h3>
            <button type="button" class="btn btn-primary convert-type-btn"
                convert-type="horizontal">Horizontal</button>
            <button type="button" class="btn btn-primary convert-type-btn" convert-type="vertical">Vertical</button>
        </div>
    </div>

    <div class="mt-3 container text-center mb-5" id="convertBtnContainer">
        <button type="button" class="btn btn-warning" id="convertBtn">Convertir a PDF</button>
    </div>

    <div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true"
        id="modalSizeInput">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSizeModalLabel">Agregar Tamaño Personalizado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="customSizeForm">

                        <div class="mb-3">
                            <label for="customHeight" class="form-label">Alto:</label>
                            <input type="text" class="form-control" id="customHeight" name="customHeight" required>
                        </div>
                        <div class="mb-3">
                            <label for="customWidth" class="form-label">Ancho:</label>
                            <input type="text" class="form-control" id="customWidth" name="customWidth" required>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary add-custom-size-btn" id="addCustomSizeBtn"
                        data-bs-dismiss="modal">Agregar</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
