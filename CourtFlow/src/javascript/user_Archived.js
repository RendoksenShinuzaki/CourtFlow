// document.addEventListener("DOMContentLoaded", function () {
//     toggleTable(); // Call the function to set the initial visibility based on the selected option

//     function toggleTable() {
//         var InterpreterOptions = document.getElementById("InterpreterOptions"); // Corrected id
//         var ArchivePreTrial = document.getElementById("ArchivePreTrial");
//         var ArchiveHearing = document.getElementById("ArchiveHearing");

//         if (InterpreterOptions.value === "ForArchivePreTrial") {
//             ArchivePreTrial.style.display = "block";
//             ArchiveHearing.style.display = "none";
            
//         } else if (InterpreterOptions.value === "ForArchiveHearing"){
//             ArchivePreTrial.style.display = "none";
//             ArchiveHearing.style.display = "block";
//         }
//     }

//     // Add an event listener for changes to the dropdown selection
//     var InterpreterOptionsDropDown = document.getElementById("InterpreterOptions");
//     InterpreterOptionsDropDown.addEventListener("change", toggleTable);
// });