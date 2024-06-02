const addPenalties = document.getElementById("addPenalty");
const cancelPenalties = document.getElementById("cancelPenalty");
const addPenaltyContainer = document.getElementById("addPenaltyContainer");

addPenalties.addEventListener("click", () => {
    addPenaltyContainer.classList.remove("hidden");
});

cancelPenalties.addEventListener("click", () => {
    addPenaltyContainer.classList.add("hidden");
})

const editButtons = document.querySelectorAll(".edit-button");
const editFormContainer = document.getElementById("editFormContainer");
const editCancelButton = document.getElementById("editCancel");
const editForm = document.getElementById("editForm");

editButtons.forEach((editButton) => {
    editButton.addEventListener("click", () => {
      // Show the edit form container by removing the 'hidden' class
      editFormContainer.classList.remove("hidden");
  
      // Get the table row containing the clicked edit button
      const tableRow = editButton.closest("tr");
  
      // Retrieve the user data from the table row
      const id = tableRow.cells[0].textContent;
      const republicAct = tableRow.cells[2].textContent;
      const article = tableRow.cells[3].textContent;
      const section = tableRow.cells[4].textContent;
      const caseFine = tableRow.cells[5].textContent;
      const bailable = tableRow.cells[6].textContent;
  
      // Populate the form fields with the user data
      editForm.id.value = id;
      editForm.editRepublicAct.value = republicAct;
      editForm.editArticle.value = article;
      editForm.editSection.value = section;
      editForm.editCaseFine.value = caseFine;
      editForm.editBailable.value = bailable;

      const editCaseFineInput = document.getElementById("editCaseFine");
      const labelEditCaseFineInput = document.getElementById("labelEditCaseFine");
      if(editForm.editBailable.value == 1){
        labelEditCaseFineInput.classList.remove("hidden");
        editCaseFineInput.classList.remove("hidden");
        editCaseFineInput.setAttribute('required', 'required');
      }else{
        labelEditCaseFineInput.classList.add("hidden");
        editCaseFineInput.classList.add("hidden");
        editCaseFineInput.removeAttribute('required');
        editForm.editCaseFine.value = 0;
      }
    });
  });

  editCancelButton.addEventListener("click", () => {
    editFormContainer.classList.add("hidden");
});

const deleteButtons = document.querySelectorAll(".delete-button");
const deleteFormContainer = document.getElementById("deleteFormContainer");
const deleteCancelButton = document.getElementById("deleteCancel");
const deleteForm = document.getElementById("deleteForm");

deleteButtons.forEach((deleteButton) => {
  deleteButton.addEventListener("click", () => {
    // Show the delete form container by removing the 'hidden' class
    deleteFormContainer.classList.remove("hidden");

    // Get the table row containing the clicked delete button
    const tableRow = deleteButton.closest("tr");

    // Retrieve the user data from the table row
    const id = tableRow.cells[0].textContent;
    

    // Populate the form fields with the user data
    deleteForm.id.value = id;
    
  });
});

deleteCancelButton.addEventListener("click", () => {
  deleteFormContainer.classList.add("hidden");
});