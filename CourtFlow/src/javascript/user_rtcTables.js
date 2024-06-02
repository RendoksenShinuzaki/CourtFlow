document.addEventListener("DOMContentLoaded", function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        var choices = document.getElementById("choices");
        var Warrant = document.getElementById("Warrant");
        var Subpoena = document.getElementById("Subpoena");

        if (choices.value === "ForWarrant") {
            Warrant.style.display = "block";
            Subpoena.style.display = "none";
        } 
        if (choices.value === "ForSubpoena"){
            Warrant.style.display = "none";
            Subpoena.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var choicesDropdown = document.getElementById("choices");
    choicesDropdown.addEventListener("change", toggleTable);
});

document.addEventListener("DOMContentLoaded", function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        var Interpreterchoices = document.getElementById("Interpreterchoices"); // Corrected id
        var PreTrial = document.getElementById("PreTrial");
        var Hearing = document.getElementById("Hearing");

        if (Interpreterchoices.value === "ForPreTrial") {
            PreTrial.style.display = "block";
            Hearing.style.display = "none";
            
        } else if (Interpreterchoices.value === "ForHearing"){
            PreTrial.style.display = "none";
            Hearing.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var InterpreterchoicesDropDown = document.getElementById("Interpreterchoices");
    InterpreterchoicesDropDown.addEventListener("change", toggleTable);
});