var decimal = 0;

function convert() {
    var fromElement = document.getElementById('from');
    var toElement = document.getElementById('to');
    var inputElement = document.getElementById('input');
    var displayElement = document.getElementById('display');

    var fromValue = parseFloat(fromElement.value);
    var toValue = parseFloat(toElement.value);
    var inputValue = parseFloat(inputElement.value);

    if (!isNaN(inputValue)) {
        var result = inputValue * fromValue / toValue;
        displayElement.value = result;
    }
}

function clearInput() {
    var inputElement = document.getElementById('input');
    var displayElement = document.getElementById('display');

    inputElement.value = 0;
    displayElement.value = 0;
    decimal = 0;
}
