// //PAO
// const ViewQRModal = document.getElementById("ViewQRModal");
// const AddClose = document.getElementById("addClose");

// document.addEventListener("DOMContentLoaded", function() {
//     // Select all elements with the class "QRButton"
//     const QRButtons = document.querySelectorAll('.QRButton');

//     QRButtons.forEach(function(button) {
//         button.addEventListener('click', function() {
//             // Get the "data-id" attribute value from the clicked button
//             var id = this.getAttribute('data-id');

//             // Construct the QR code URL with the prescription ID and username
//             var qrCodeUrl = `http://localhost/CourtFlow/src/pages/user/userQrRedirect.php?id=${id}`;

//             // Clear the content of the qrcode element
//             document.getElementById("qrcode").innerHTML = '';

//             // Display the QR code using html5-qrcode
//             var qrcode = new QRCode(document.getElementById("qrcode"), {
//                 text: qrCodeUrl,
//                 width: 128,
//                 height: 128,
//                 correctLevel: QRCode.CorrectLevel.L
//             });

//             // Open the modal
//             ViewQRModal.classList.remove("hidden");
//         });
//     });
// });

// AddClose.addEventListener("click", () => {
//     ViewQRModal.classList.add("hidden");
// });

// //Fiscal
// const FiscalViewQRModal = document.getElementById("FiscalViewQRModal");
// const FiscalAddClose = document.getElementById("FiscaladdClose");

// document.addEventListener("DOMContentLoaded", function() {
//     // Select all elements with the class "QRButton"
//     const FiscalQRButton = document.querySelectorAll('.FiscalQRButton');

//     FiscalQRButton.forEach(function(button) {
//         button.addEventListener('click', function() {
//             // Get the "data-id" attribute value from the clicked button
//             var id = this.getAttribute('data-id');

//             // Construct the QR code URL with the prescription ID and username
//             var qrCodeUrl = `http://localhost/CourtFlow/src/pages/user/userQrRedirect.php?id=${id}`;

//             // Clear the content of the qrcode element
//             document.getElementById("qrcode").innerHTML = '';

//             // Display the QR code using html5-qrcode
//             var qrcode = new QRCode(document.getElementById("qrcode"), {
//                 text: qrCodeUrl,
//                 width: 128,
//                 height: 128,
//                 correctLevel: QRCode.CorrectLevel.L
//             });

//             // Open the modal
//             FiscalViewQRModal.classList.remove("hidden");
//         });
//     });
// });

// FiscalAddClose.addEventListener("click", () => {
//     FiscalViewQRModal.classList.add("hidden");
// });

//OCC
const OCCViewQRModal = document.getElementById("OCCViewQRModal");
const OCCaddClose = document.getElementById("OCCaddClose");

document.addEventListener("DOMContentLoaded", function() {
    // Select all elements with the class "QRButton"
    const OCCQRButton = document.querySelectorAll('.OCCQRButton');

    OCCQRButton.forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the "data-id" attribute value from the clicked button
            var id = this.getAttribute('data-id');

            // Construct the QR code URL with the prescription ID and username
            const qrCodeUrl = `https://courtflow.online/CourtFlow/src/pages/user/userQrRedirect.php?id=${id}`;

            // Clear the content of the qrcode element
            document.getElementById("qrcode").innerHTML = '';

            // Display the QR code using html5-qrcode
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: qrCodeUrl,
                width: 128,
                height: 128,
                correctLevel: QRCode.CorrectLevel.L
            });

            // Open the modal
            OCCViewQRModal.classList.remove("hidden");
        });
    });
});

OCCaddClose.addEventListener("click", () => {
    OCCViewQRModal.classList.add("hidden");
});

