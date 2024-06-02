document.getElementById('docketNumber').addEventListener('change', function() {
    var docketNumberSelect = document.getElementById('docketNumber');
    var warrantFileNameSelect = document.getElementById('warrantFileName');

    var selectedDocketNumber = docketNumberSelect.value;

    // Update warrantFileName based on selected docketNumber
    warrantFileNameSelect.value = selectedDocketNumber; // You may need to adjust this line based on your logic
    
});