
document.addEventListener("DOMContentLoaded", function () {

    const uploadBtn = document.getElementById("uploadBtn");
    const convertBtn = document.getElementById("convertBtn");

    const sizeBtnContainer = document.querySelector(".size-button-container");

    const imgBtnContainer = document.querySelector(".img-btn-container");
    const customSizeBtnContainer = document.querySelector(".custom-size-button-container");
    const addCustomSizeBtnModal = document.querySelector(".add-custom-size-btn");

    const imgSizeInput = document.getElementById("imgSizeInput");
    const convertTypeBtnContainer = document.querySelector(".convert-type-btn-container");

    // Función para agregar una clase "active" a los botones de tamaño
    function toggleSizeBtnActive(event) {
        if (event.target.classList.contains("size-btn")) {
            event.target.classList.toggle("active");
            imgSizeInput.value = event.target.getAttribute("data-size");

            const activeSizeBtns = document.querySelectorAll(".size-btn.active");
            const selectedSizes = Array.from(activeSizeBtns).map(btn => btn.getAttribute("data-size"));

            const newURL = "home-" + selectedSizes.join("-");
            window.history.replaceState(null, "", newURL);
        }
    }

    function addCustomSizeBtnF() {
        const customHeightInput = document.getElementById("customHeight");
        const customWidthInput = document.getElementById("customWidth");
    
        // Obtener los valores ingresados por el usuario
        const customHeight = parseFloat(customHeightInput.value);
        const customWidth = parseFloat(customWidthInput.value);
    
        // Verificar si los valores son numéricos y mayores a 0
        if (!isNaN(customHeight) && !isNaN(customWidth) && customHeight > 0 && customWidth > 0) {

            customHeightInput.value = "";
            customWidthInput.value = "";
    
            const customSizeBtn = createCustomSizeBtn(customHeight, customWidth);
            customSizeBtnContainer.insertBefore(customSizeBtn, customSizeBtnContainer.lastChild);
        } else {
            alert("Debe ingresar valores númericos mayores a cero!");
        }
    }
    
    // Función para crear un botón de tamaño personalizado
    function createCustomSizeBtn(height, width) {
        const customSizeBtn = document.createElement("button");
        customSizeBtn.classList.add("btn", "btn-primary", "size-btn", "custom-size-btn");
        customSizeBtn.textContent = `${height}cm x ${width}cm`;
        customSizeBtn.setAttribute("type", "button");
        customSizeBtn.setAttribute("data-size", `${height}x${width}`);

        const deleteBtn = document.createElement("span");
        deleteBtn.innerHTML = "&times;";
        deleteBtn.classList.add("custom-size-btn-delete");

        deleteBtn.addEventListener("click", function () {
            customSizeBtn.remove();
        });

        customSizeBtn.appendChild(deleteBtn);
        return customSizeBtn;
    }

    // Función para agregar o quitar la clase "active" a los botones de imagen
    function toggleImageBtnActive(event) {
        if (event.target.classList.contains("image-btn")) {
            event.target.classList.toggle("active");
        }
    }

    // Función para agregar o quitar la clase "active" a los botones de tipo de conversión
    function toggleConvertTypeBtnActive(event) {
        if (event.target.classList.contains("convert-type-btn")) {
            event.target.classList.toggle("active");
        }
    }

    imgBtnContainer.addEventListener("click", toggleImageBtnActive);
    sizeBtnContainer.addEventListener("click", toggleSizeBtnActive);
    addCustomSizeBtnModal.addEventListener("click", addCustomSizeBtnF);
    convertTypeBtnContainer.addEventListener("click", toggleConvertTypeBtnActive);
    customSizeBtnContainer.addEventListener("click", toggleSizeBtnActive);

    function handleImageUploadSuccess(data) {
        document.getElementById("image").value = "";

        const successAlertContainer = document.querySelector("#successAlert");
        const successAlert = document.createElement("div");

        successAlert.classList.add("alert", "alert-success", "mt-3");
        successAlert.style.display = "flex";

        const alertText = document.createElement("div");
        alertText.textContent = "Imagen cargada correctamente: " + data.imageName;

        const imgPreview = document.createElement("img");
        imgPreview.src = data.imagePath;
        imgPreview.style.maxWidth = "80px";
        imgPreview.style.maxHeight = "80px";

        successAlert.appendChild(alertText);
        successAlert.appendChild(imgPreview);
        successAlert.style.justifyContent = "space-between";
        successAlert.style.alignItems = "center";
        successAlertContainer.appendChild(successAlert);

        successAlertContainer.style.display = "block";

        const uploadedImgContainer = document.querySelector("#uploadedImgs");
        const imgBtn = document.createElement("button");

        imgBtn.classList.add("btn", "btn-primary", "image-btn");
        imgBtn.textContent = data.imageName;
        imgBtn.setAttribute("type", "button");
        imgBtn.setAttribute("data-img", data.imagePath);
        uploadedImgContainer.appendChild(imgBtn);
        uploadedImgContainer.style.display = "block";

        const sizeBtnsContainer = document.getElementById("sizeBtnsContainer");
        sizeBtnsContainer.style.display = "block";

        const convertBtnContainer = document.getElementById("convertBtnContainer");
        convertBtnContainer.style.display = "block";

        const convertTypeBtnContainer = document.getElementById("conversionTypeContainer");
        convertTypeBtnContainer.style.display = "block";

        const defaultSizeBtns = document.querySelectorAll(".default-size-btn");

        defaultSizeBtns.forEach(button => {
            const deleteBtn = document.createElement("span");
            deleteBtn.innerHTML = "&times;";
            deleteBtn.classList.add("custom-size-btn-delete");
            deleteBtn.addEventListener("click", function () {
                const dataSize = button.getAttribute("data-size");
                const matchingButton = document.querySelector(
                    `.default-size-btn[data-size="${dataSize}"]`);

                if (matchingButton) {
                    matchingButton.remove();
                }
            });

            button.appendChild(deleteBtn);
        });

    }

    uploadBtn.addEventListener("click", function () {
        const imageInput = document.getElementById("image");
        const image = imageInput.files[0];

        if (!image) {
            alert("Por favor, seleccione una imagen.");
            return;
        }

        const formData = new FormData();
        formData.append("image", image);

        fetch(uploadImageUrl, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    handleImageUploadSuccess(data);

                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });

    });

    convertBtn.addEventListener("click", function () {
        const selectedSizeBtns = document.querySelectorAll(".size-btn.active");
        const selectedImgBtns = document.querySelectorAll(".image-btn.active");
        const selectedConvertBtns = document.querySelectorAll(".convert-type-btn.active")
        const selectedData = [];

        selectedImgBtns.forEach(button => {
            const imgUrl = button.getAttribute("data-img");
            const imgName = button.textContent;
            selectedData.push({ imgUrl, imgName });
        });

        if (selectedSizeBtns.length === 0 || selectedImgBtns.length === 0 || selectedConvertBtns.length === 0) {
            alert("¡Selecciona al menos un tamaño, una imagen y un tipo de conversión!");

        } else {
            const selectedSizes = [];
            selectedSizeBtns.forEach(button => {
                selectedSizes.push(button.getAttribute("data-size"));
            });

            const selectedConvertType = [];
            selectedConvertBtns.forEach(button => {
                selectedConvertType.push(button.getAttribute("convert-type"))
            })

            fetch(convertToPdfUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    selectedSizes: selectedSizes,
                    selectedData: selectedData,
                    selectedConvertType: selectedConvertType
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);

                        if (data.pdfPaths && data.pdfPaths.length > 0) {
                            data.pdfPaths.forEach(pdfPath => {
                                const link = document.createElement('a');
                                link.href = pdfPath;

                                const urlSegments = pdfPath.split('/');
                                const fileName = urlSegments[urlSegments.length - 1];

                                link.download = fileName;
                                link.style.display = 'none';
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            });
                        }
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });

});


