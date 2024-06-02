const viewFileButtons = document.querySelectorAll(".PaoEdit-button");
const viewFileFormContainer = document.getElementById("PaoEditFormContainer");
const viewFileBackButton = document.getElementById("viewFileBackButton");
const fileIdInput = document.getElementById("fileId");

viewFileButtons.forEach((viewFileButton) => {
    viewFileButton.addEventListener("click", () => {
      // Show the edit form container by removing the 'hidden' class
      viewFileFormContainer.classList.remove("hidden");
  
      // Get the table row containing the clicked edit button
      const tableRow = viewFileButton.closest("tr");
  
      // Retrieve the user data from the table row
      const FromEmployee = tableRow.cells[2].textContent;
      const ComplainantName = tableRow.cells[2].textContent;
      const AccusedName = tableRow.cells[3].textContent;
      const FileName = tableRow.cells[4].textContent;
      const fileId = tableRow.cells[0].textContent;
  
      // Populate the form fields with the user data
      viewFileForm.FromEmployee.value = FromEmployee;
      viewFileForm.ComplainantName.value = ComplainantName;
      viewFileForm.AccusedName.value = AccusedName;
      viewFileForm.FileName.value = FileName;
      fileIdInput.value = fileId;
      
    });
  });

  viewFileBackButton.addEventListener("click", () => {
    viewFileFormContainer.classList.add("hidden");
});