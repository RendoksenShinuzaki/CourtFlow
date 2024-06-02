const addButtons = document.getElementById("addBranch");
const addBranchContainer = document.getElementById("addBranchContainer");
const addCancel = document.getElementById("addCancel");

addButtons.addEventListener("click", () => {
    addBranchContainer.classList.remove("hidden");
});

addCancel.addEventListener("click", () => {
    addBranchContainer.classList.add("hidden");
});

