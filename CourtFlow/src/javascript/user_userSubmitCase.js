//  function handleFileSelect(event) {
//      const files = event.target.files;
//      const allowedFileTypes = [
//          "application/msword",
//          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
//          "application/pdf", // Add PDF file type
//      ];
//      const fileIsValid = Array.from(files).every(file => allowedFileTypes.includes(file.type));

//      if (!fileIsValid) {
//          alert("Please select only Word documents (doc, docx) or PDF files.");
//          event.target.value = null; // Clear the file input
//      }
//  }

// function updateWarrantFileName() {
//     var docketNumberSelect = document.getElementById('docketNumber');
//     var selectedIndex = docketNumberSelect.selectedIndex;
//     var selectedFilename = docketNumberSelect.options[selectedIndex].getAttribute('data-filename');
//     document.getElementById('warrantFileName').value = selectedFilename;
// }

// // Call the function on page load
// document.addEventListener('DOMContentLoaded', function () {
//     updateWarrantFileName();
// });

// // Attach the function to the change event of the docketNumber select
// document.getElementById('docketNumber').addEventListener('change', updateWarrantFileName);
