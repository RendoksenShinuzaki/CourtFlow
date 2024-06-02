const fiscalReturnButtons = document.querySelectorAll(".fiscalReturn-button");
const fiscalReturnFormContainer = document.getElementById("fiscalReturnFormContainer");
const FiscalReturnCancel = document.getElementById("FiscalReturnCancel");
const fiscalReturnForm = document.getElementById("fiscalReturnForm");

fiscalReturnButtons.forEach((fiscalReturnButton) => {
    fiscalReturnButton.addEventListener("click", () => {
      // Show the edit form container by removing the 'hidden' class
      fiscalReturnFormContainer.classList.remove("hidden");
  
      // Get the table row containing the clicked edit button
      const tableRow = fiscalReturnButton.closest("tr");
  
      // Retrieve the user data from the table row
      const id = tableRow.cells[0].textContent;
      const toEmployee = tableRow.cells[1].textContent;
  
      // Populate the form fields with the user data
      fiscalReturnForm.id.value = id;
      fiscalReturnForm.toEmployee.value = toEmployee;
    });
  });

  FiscalReturnCancel.addEventListener("click", () => {
    fiscalReturnFormContainer.classList.add("hidden");
});

 const OCCReturnButtons = document.querySelectorAll(".OCCReturn-button");
 const OCCReturnFormContainer = document.getElementById("OCCReturnFormContainer");
 const OCCReturnCancel = document.getElementById("OCCReturnCancel");
 const OCCReturnForm = document.getElementById("OCCReturnForm");

 OCCReturnButtons.forEach((OCCReturnButton) => {
  OCCReturnButton.addEventListener("click", () => {
    // Show the edit form container by removing the 'hidden' class
    OCCReturnFormContainer.classList.remove("hidden");

    // Get the table row containing the clicked edit button
    const tableRow = OCCReturnButton.closest("tr");

    // Retrieve the user data from the table row
    const id = tableRow.cells[0].textContent;
    const toEmployee = tableRow.cells[1].textContent;

    // Populat the form fields with the user data

    OCCReturnForm.id.value = id;
    OCCReturnForm.toEmployee.value = toEmployee;
  });
 });

 OCCReturnCancel.addEventListener("click", () => {
  OCCReturnFormContainer.classList.add("hidden");
 });
 
 const RTCReturnButtons = document.querySelectorAll(".RTCReturn-button");
 const RTCReturnFormContainer = document.getElementById("RTCReturnFormContainer");
 const RTCReturnCancel = document.getElementById("RTCReturnCancel");
 const RTCReturnForm = document.getElementById("RTCReturnForm");

 RTCReturnButtons.forEach((RTCReturnButton) => {
  RTCReturnButton.addEventListener("click", () => {
    // Show the edit form container by removing the 'hidden' class
    RTCReturnFormContainer.classList.remove("hidden");

    // Get the table row containing the clicked edit button
    const tableRow = RTCReturnButton.closest("tr");

    // Retrieve the user data from the table row
    const id = tableRow.cells[0].textContent;
    const toEmployee = tableRow.cells[1].textContent;

    // Populat the form fields with the user data

    OCCReturnForm.id.value = id;
    OCCReturnForm.toEmployee.value = toEmployee;
  });
 });

 OCCReturnCancel.addEventListener("click", () => {
  OCCReturnFormContainer.classList.add("hidden");
 });

//  document.addEventListener("DOMContentLoaded", function () {
//   toggleTable(); // Call the function to set the initial visibility based on the selected option

//   function toggleTable() {
//       var ClerkOptions = document.getElementById("ClerkOption"); // Corrected id
//       var Guilty = document.getElementById("GuiltyTable");
//       var HearingSummary = document.getElementById("ShowSummaryTable");

//       if (ClerkOptions.value === "ShowGuiltyTable") {
//         Guilty.style.display = "block";
//         HearingSummary.style.display = "none";
          
//       } else if (ClerkOptions.value === "ForHearing"){
//         Guilty.style.display = "none";
//         HearingSummary.style.display = "block";
//       }
//   }

//   // Add an event listener for changes to the dropdown selection
//   var ClerkOptionsDropDown = document.getElementById("ClerkOption");
//   ClerkOptionsDropDown.addEventListener("change", toggleTable);
// });