const viewReasonButtons = document.querySelectorAll(".viewReason-button");
const viewReasonFormContainer = document.getElementById("viewReasonFormContainer");
const viewReasonBackButton = document.getElementById("viewReasonBackButton");
const viewReasonForm = document.getElementById("viewReasonForm");

viewReasonButtons.forEach((viewReasonButton) => {
    viewReasonButton.addEventListener("click", () => {
      // Show the edit form container by removing the 'hidden' class
      viewReasonFormContainer.classList.remove("hidden");
  
      // Get the table row containing the clicked edit button
      const tableRow = viewReasonButton.closest("tr");
  
      // Retrieve the user data from the table row
      const reason = tableRow.cells[5].textContent;
  
      // Populate the form fields with the user data
      viewReasonForm.reason.value = reason;
    });
  });

  viewReasonBackButton.addEventListener("click", () => {
    viewReasonFormContainer.classList.add("hidden");
});

const viewCaseButtons = document.querySelectorAll(".viewCase-button");
const viewCaseFormContainer = document.getElementById("viewCaseFormContainer");
const viewCaseBackButton = document.getElementById("viewCaseBackButton");
const viewCaseForm = document.getElementById("viewCaseForm");

viewCaseButtons.forEach((viewCaseButton) => {
    viewCaseButton.addEventListener("click", () => {
      // Show the edit form container by removing the 'hidden' class
      viewCaseFormContainer.classList.remove("hidden");
  
      // Get the table row containing the clicked edit button
      const tableRow = viewCaseButton.closest("tr");
  
      // Retrieve the user data from the table row
      //const CaseId = tableRow.cells[3].textContent;
      const FileName = tableRow.cells[6].textContent;
      
      // Populate the form fields with the user data
      viewCaseForm.FileName.value = FileName;
    });
  });

  viewCaseBackButton.addEventListener("click", () => {
    viewCaseFormContainer.classList.add("hidden");
});