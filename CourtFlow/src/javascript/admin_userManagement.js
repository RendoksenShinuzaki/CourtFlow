const addFormContainer = document.getElementById("addFormContainer");
const addCancelButton = document.getElementById("addCancel");
const addButtons = document.getElementById("addButton");

addButtons.addEventListener("click", () => {
    addFormContainer.classList.remove("hidden");
});

addCancelButton.addEventListener("click", () => {
    addFormContainer.classList.add("hidden");
});

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
      const lastName = tableRow.cells[3].textContent;
      const firstName = tableRow.cells[4].textContent;
      const middleName = tableRow.cells[5].textContent;
      const gender = tableRow.cells[6].textContent;
      const contact = tableRow.cells[9].textContent;
      const password = tableRow.cells[10].textContent;
  
      // Populate the form fields with the user data
      editForm.id.value = id;
      editForm.editLname.value = lastName;
      editForm.editFname.value = firstName;
      editForm.editMname.value = middleName;
      editForm.editGender.value = gender;
      editForm.editContactNum.value = contact;
      editForm.editPassword.value = password;
    });
  });

editCancelButton.addEventListener("click", () => {
editFormContainer.classList.add("hidden");
});

document.addEventListener('DOMContentLoaded', function () {
    var roleDropdown = document.getElementById('role');
    var branchContainer = document.getElementById('branchContainer');
    var branchDropdown = document.getElementById('branch');

    roleDropdown.addEventListener('change', function () {
        var selectedRole = roleDropdown.value;
        if (selectedRole === 'RTC') {
            branchContainer.style.display = 'block';
            // Set the default branch value to "none" when RTC is selected
            branchDropdown.value = 'none';
        } else {
            branchContainer.style.display = 'none';
        }
    });

    // Hide the branchContainer on page load if RTC is not initially selected
    if (roleDropdown.value !== 'RTC') {
        branchContainer.style.display = 'none';
    }
});
  