var now;
var firstDayOfWeek;
var currentWeek = 0;

var selectedDay = -1;
var selectedHour = -1;
var clickedDiv = null;

var selectedFilter = 0;
var selectedOption = 0;
var selectedInModal = 0;

var uslugi = [];
var fryzjerzy = [];
var wizyty = [];

var blad=0;

var selectedName = "";
var selectedSurname = "";
var selectedPhone = "";
var selectedEmail = "";
var selectedService;
var selectedWorker;
var selectedDzienTygodnia;

aktualne_wizyty = [];

function updateCurrentWeek(now) {
    console.log("Update");
    const dateElement = document.getElementById('date');

    firstDayOfWeek = new Date(now);
    var lastDayOfWeek = new Date(now);

    firstDayOfWeek.setDate(firstDayOfWeek.getDate() - firstDayOfWeek.getDay() + 1);
    lastDayOfWeek = addDays(firstDayOfWeek, 6);

    const options = { month: 'short', day: 'numeric' };
    const firstDayFormatted = firstDayOfWeek.toLocaleDateString('pl-PL', options);
    const lastDayFormatted = lastDayOfWeek.toLocaleDateString('pl-PL', options);

    dateElement.textContent = `${firstDayFormatted} - ${lastDayFormatted}`;

    document.getElementById('monday-date').textContent = firstDayFormatted;
    document.getElementById('tuesday-date').textContent = addDays(firstDayOfWeek, 1).toLocaleDateString('pl-PL', options);
    document.getElementById('wednesday-date').textContent = addDays(firstDayOfWeek, 2).toLocaleDateString('pl-PL', options);
    document.getElementById('thursday-date').textContent = addDays(firstDayOfWeek, 3).toLocaleDateString('pl-PL', options);
    document.getElementById('friday-date').textContent = addDays(firstDayOfWeek, 4).toLocaleDateString('pl-PL', options);

    var firstDay = new Date(firstDayOfWeek).setHours(0);
    var lastDay = new Date(lastDayOfWeek).setHours(0);

    clearCalendar();

    wizyty.forEach(wizyta => {
        if(wizyta.fryzjer==selectedWorker && selectedFilter==1)
        if (new Date(wizyta.data).setHours(wizyta.godzina) >= firstDay && new Date(wizyta.data).setHours(wizyta.godzina) <= lastDay) {
            var selector = '.hour[hour="' + wizyta.godzina + '"][day="' + (new Date(wizyta.data).getDay() - 1) + '"]';
            aktualne_wizyty.push(selector);
        }
        if(wizyta.usluga==selectedService && selectedFilter==0)
        if (new Date(wizyta.data).setHours(wizyta.godzina) >= firstDay && new Date(wizyta.data).setHours(wizyta.godzina) <= lastDay) {
            var selector = '.hour[hour="' + wizyta.godzina + '"][day="' + (new Date(wizyta.data).getDay() - 1) + '"]';
            aktualne_wizyty.push(selector);
        }
    });

    setCalendar();
}


function clearCalendar() {
    if (aktualne_wizyty != null) {
        aktualne_wizyty.forEach(selector => {
            var divWithAttributes = document.querySelector(selector);
            divWithAttributes.style.backgroundColor = "#fff";
            divWithAttributes.textContent = "Wolne";
        });
    }
    aktualne_wizyty = [];
}


function setCalendar() {
    if (aktualne_wizyty != null) {
        aktualne_wizyty.forEach(selector => {
            var divWithAttributes = document.querySelector(selector);
            divWithAttributes.style.backgroundColor = "#b52121";
            divWithAttributes.textContent = "Zajęte";
        });
    }
}


window.onload = onLoad;

function onLoad() {
    getCurrentDate();
    onFilterSelectChange();

    document.getElementById("modalForm").addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    if(blad==1)
    {
        blad=0;
        openModal(errorDay, errorHour, errorDiv);

    }
}

function getCurrentDate() {
    currentWeek = 0;
    now = new Date();
    updateCurrentWeek(now);

    document.getElementById("left").style.backgroundColor = "#3e688a";
    document.getElementById("left").style.borderColor = "#3e688a";

    document.getElementById("right").style.backgroundColor = "#2196f3";
    document.getElementById("right").style.borderColor = "#2196f3";
}

function weekBack() {
    if (currentWeek > 0) {
        currentWeek -= 1;
        now.setDate(now.getDate() - 7);
        updateCurrentWeek(now);
        if (currentWeek == 0) {
            document.getElementById("left").style.backgroundColor = "#3e688a";
            document.getElementById("left").style.borderColor = "#3e688a";
        }
    }

    document.getElementById("right").style.backgroundColor = "#2196f3";
    document.getElementById("right").style.borderColor = "#2196f3";
}

function weekForward() {
    if (currentWeek < 4) {
        currentWeek += 1;
        now.setDate(now.getDate() + 7);
        updateCurrentWeek(now);
        if (currentWeek == 4) {

            document.getElementById("right").style.backgroundColor = "#3e688a";
            document.getElementById("right").style.borderColor = "#3e688a";
        }
    }

    document.getElementById("left").style.backgroundColor = "#2196f3";
    document.getElementById("left").style.borderColor = "#2196f3";
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function openModal(day, hour, div) {
    // if (div.textContent == "Zajęte") {
    //     return;
    // }

    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'block';
    modalBackground.style.zIndex = 3;
    
    document.getElementById("dzien_tygodnia").value=day;
    selectedDay = day;
    selectedHour = hour;
    clickedDiv = div;

    var timeInput = document.getElementById("czas");

    var hours = hour;
    var minutes = 0;

    minutes = minutes < 10 ? "0" + minutes : minutes;
    hours = hours < 10 ? "0" + hours : hours;
    timeInput.value = hours + ":" + minutes;

    var dateInput = document.getElementById("termin");
    
    console.log(firstDayOfWeek, ", ",day);
    var date = addDays(firstDayOfWeek, day);

    var year = date.getFullYear();

    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = (date.getDate()).toString().padStart(2, '0');

    selectedDay = year + "-" + month + "-" + day;
    dateInput.value = selectedDay;

}

function empty() {

}

function closeModal() {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'none';
    document.getElementById("modal-error").innerHTML="";
}

function saveModal() {

    document.getElementById("modal-error").innerHTML="";
    var good = true;

    if (document.getElementById("imie").value == "") {
        good = false;
        var imieInput = document.getElementById("imie");
        showError(imieInput, "podaj imię");
    }

    if (document.getElementById("nazwisko").value == "") {
        good = false;
        var nazwiskoInput = document.getElementById("nazwisko");
        showError(nazwiskoInput, "podaj nazwisko");
    }

    if (document.getElementById("phone").value == "" && document.getElementById("email").value == "") {
        good = false;
        var telefon = document.getElementById("phone");
        showError(telefon, "podaj numer");
        var email = document.getElementById("email");
        showError(email, "podaj email");
    }

    if (good) {
        selectedName = document.getElementById("imie").value;
        selectedSurname = document.getElementById("nazwisko").value;
        selectedPhone = document.getElementById("phone").value;
        selectedEmail = document.getElementById("email").value;
        document.getElementById("fryzjer").value = selectedWorker;
        document.getElementById("usluga").value = selectedService;


        const modalBackground = document.getElementById('modalBackground');
        modalBackground.style.display = 'none';
        clickedDiv.style.backgroundColor = "#b52121";
        clickedDiv.textContent = "Zajęte";
        wizyty.push({ data: selectedDay, godzina: selectedHour });
        var selector = '.hour[hour="' + selectedHour + '"][day="' + (new Date(selectedDay).getDay() - 1) + '"]';
        aktualne_wizyty.push(selector);
        updateCurrentWeek(firstDayOfWeek);
        
        return true;
    }
    return false;


}

function showError(element, error) {
    var errorInput = element;
    errorInput.setAttribute('readonly', true);
    errorInput.style.backgroundColor = "red";
    errorInput.value = error;

    setTimeout(function () {
        errorInput.style.backgroundColor = "white";
        errorInput.value = "";
        errorInput.removeAttribute('readonly');
    }, 1500);
}

function onFilterSelectChange() {
    var firstSelect = document.getElementById("calendar_filter");

    switch (firstSelect.value) {
        case "service":
            selectedFilter = 0;
            document.getElementById("usluga_text").textContent = "Fryzjer: ";
            addOptionsToSecondSelect(uslugi);
            addOptionsToModal(fryzjerzy);
            break;
        case "worker":
            selectedFilter = 1;
            document.getElementById("usluga_text").textContent = "Usługa: ";
            addOptionsToSecondSelect(fryzjerzy);
            addOptionsToModal(uslugi);
            break;
        default:
            break;
    }
    
    updateCurrentWeek(now);
}

function addOptionsToModal(options) {
    var opcjaSelect = document.getElementById("opcja");
    opcjaSelect.innerHTML = "";
    var i = 0;
    options.forEach(function (optionText) {
        var option = document.createElement("option");
        option.text = optionText;
        option.value = i;
        opcjaSelect.add(option);
        i++;
    });
}

function addOptionsToSecondSelect(options) {
    var secondSelect = document.getElementById("filter_items");
    secondSelect.innerHTML = "";
    var i = 0;
    options.forEach(function (optionText) {
        var option = document.createElement("option");
        option.text = optionText;
        option.value = i;
        secondSelect.add(option);
        i++;
    });

    onOptionSelectChange();
}

function onOptionSelectChange() {

    var secondSelect = document.getElementById("filter_items");
    selectedOption = secondSelect.value;
    if (selectedFilter != 0) {
        selectedWorker = fryzjerzy[selectedOption];
    }
    else {
        selectedService = uslugi[selectedOption];
    }
    
    updateCurrentWeek(now);
}

function onServiceSelectChange() {
    if (selectedFilter == 0) {
        selectedWorker = fryzjerzy[document.getElementById("opcja").value];
    }
    else {
        selectedService = uslugi[document.getElementById("opcja").value];
    }
}

