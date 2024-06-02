document.addEventListener("DOMContentLoaded", function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        var versionOption = document.getElementById("versionOption");
        var version1Table = document.getElementById("version1Table");
        var version2Table = document.getElementById("version2Table");

        if (versionOption.value === "v1") {
            version1Table.style.display = "block";
            version2Table.style.display = "none";
        } else {
            version1Table.style.display = "none";
            version2Table.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var versionOption = document.getElementById("versionOption");
    versionOption.addEventListener("change", toggleTable);
});