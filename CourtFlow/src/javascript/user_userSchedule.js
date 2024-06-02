function setMinEndTime() {
    var startTime = document.getElementById('pretrialTimeStart').value;
    document.getElementById('pretrialTimeEnd').min = startTime;
}

document.addEventListener("DOMContentLoaded", function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        console.log("Toggle table function called");
        var ScheduleChoices = document.getElementById("ScheduleChoices"); // Corrected id
        var PreTrialSchedule = document.getElementById("PreTrialSchedule");
        var HearingSchedule = document.getElementById("HearingSchedule");

        if (ScheduleChoices.value === "ForPreTrial") {
            PreTrialSchedule.style.display = "block";
            HearingSchedule.style.display = "none";
            
        } else if (ScheduleChoices.value === "ForHearing"){
            PreTrialSchedule.style.display = "none";
            HearingSchedule.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var ScheduleChoicesDropDown = document.getElementById("ScheduleChoices");
    ScheduleChoicesDropDown.addEventListener("change", toggleTable);
});

