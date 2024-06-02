const rescheduleButtons = document.querySelectorAll(".reschedule-button");
const reScheduleFormContainer = document.getElementById("reScheduleFormContainer");
const reScheduleCancel = document.getElementById("reScheduleCancel");
const reScheduleForm = document.getElementById("reScheduleForm");

rescheduleButtons.forEach((rescheduleButton) => {
    rescheduleButton.addEventListener("click", () => {
        reScheduleFormContainer.classList.remove("hidden");

        const tableRow = rescheduleButton.closest("tr");

        const id = tableRow.cells[0].textContent;

        reScheduleForm.id.value = id;
    });
});

const hearingRescheduleButtons = document.querySelectorAll(".hearingReschedule-button");
const hearingReScheduleFormContainer = document.getElementById("HearingReScheduleFormContainer");
const hearingReScheduleCancel = document.getElementById("HearingReScheduleCancel");
const HearingReScheduleForm = document.getElementById("HearingReScheduleForm");

hearingRescheduleButtons.forEach((hearingRescheduleButton) => {
    hearingRescheduleButton.addEventListener("click", () => {
        HearingReScheduleFormContainer.classList.remove("hidden");

        const tableRow = hearingRescheduleButton.closest("tr");

        const id = tableRow.cells[0].textContent;

        HearingReScheduleForm.id.value = id;
    });

});

reScheduleCancel.addEventListener("click", () => {
    reScheduleFormContainer.classList.add("hidden");
});

document.addEventListener("DOMContentLoaded", function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        var InterpreterOptions = document.getElementById("InterpreterOptions");
        var ArchivePreTrial = document.getElementById("ArchivePreTrial");
        var ArchiveHearing = document.getElementById("ArchiveHearing");

        // console.log("Selected value:", InterpreterOptions.value);

        if (InterpreterOptions.value === "ForArchivePreTrial") {
            ArchivePreTrial.style.display = "block";
            ArchiveHearing.style.display = "none";
        } else if (InterpreterOptions.value === "ForArchiveHearing") {
            ArchivePreTrial.style.display = "none";
            ArchiveHearing.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var InterpreterOptionsDropDown = document.getElementById("InterpreterOptions");
    InterpreterOptionsDropDown.addEventListener("change", toggleTable);
});

document.addEventListener("DOMContentLoaded", function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable(){
        var InterpreterFinished = document.getElementById("InterpreterFinished");
        var HearingPending = document.getElementById("HearingPending");
        var HearingFinished = document.getElementById("HearingFinished");

        if(InterpreterFinished.value === "InterpreterHearingPending"){
            HearingPending.style.display = "block";
            HearingFinished.style.display = "none";
        } else if (InterpreterFinished.value === "InterpreterHearingFinished"){
            HearingPending.style.display = "none";
            HearingFinished.style.display = "block";
        }
    }
    // Add an event listener for changes to the dropdown selection
    var InterpreterFinishedDropDown = document.getElementById("InterpreterFinished");
    InterpreterFinishedDropDown.addEventListener("change", toggleTable);
    
});

  
