document.addEventListener('DOMContentLoaded', function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        var versionOption = document.getElementById("occVersionOption");
        var version1Table = document.getElementById("occVersion1Table");
        var version2Table = document.getElementById("occVersion2Table");

        if (versionOption.value === "v1") {
            version1Table.style.display = "block";
            version2Table.style.display = "none";
        } else {
            version1Table.style.display = "none";
            version2Table.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var versionOption = document.getElementById("occVersionOption");
    versionOption.addEventListener("change", toggleTable);
});

document.addEventListener('DOMContentLoaded', function () {
    toggleTable(); // Call the function to set the initial visibility based on the selected option

    function toggleTable() {
        var versionOption = document.getElementById("rtcVersionOption");
        var version1Table = document.getElementById("rtcVersion1Table");
        var version2Table = document.getElementById("rtcVersion2Table");
        var version3Table = document.getElementById("rtcVersion3Table");
        
        if (versionOption.value === "v1") {
            version1Table.style.display = "block";
            version2Table.style.display = "none";
            version3Table.style.display = "none";
        } if (versionOption.value === "v2"){
            version1Table.style.display = "none";
            version2Table.style.display = "block";
            version3Table.style.display = "none";
        } if (versionOption.value === "v3"){
            version1Table.style.display = "none";
            version2Table.style.display = "none";
            version3Table.style.display = "block";
        }
    }

    // Add an event listener for changes to the dropdown selection
    var versionOption = document.getElementById("rtcVersionOption");
    versionOption.addEventListener("change", toggleTable);
});