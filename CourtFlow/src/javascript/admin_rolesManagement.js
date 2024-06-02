const addButtons = document.getElementById("addButton");
const addCancelButton = document.getElementById("addCancel");
const editCancelButton = document.getElementById("editCancel");
const addFormContainer = document.getElementById("addFormContainer");
const editRoleFormContainer = document.getElementById("EditRoleFormContainer");

addButtons.addEventListener("click", () => {
    addFormContainer.classList.remove("hidden");
});

addCancelButton.addEventListener("click", () => {
    addFormContainer.classList.add("hidden");
});


const EditRoleBtn = document.querySelectorAll(".edit-button");
const editForm = document.getElementById("EditRoleForm");

EditRoleBtn.forEach((EditRoleButtons) =>{
    EditRoleButtons.addEventListener("click", () =>{

        
        editRoleFormContainer.classList.remove("hidden");

        const tableRow = EditRoleButtons.closest("tr");

        const id = tableRow.cells[0].textContent;
        const role = tableRow.cells[1].textContent;
        
        editForm.id.value = id;
        editForm.editRole.value = role;
    });
});

editCancelButton.addEventListener("click", () => {
    editRoleFormContainer.classList.add("hidden");
});