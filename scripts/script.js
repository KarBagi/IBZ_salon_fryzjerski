var now;
var firstDayOfWeek;
var currentDayOfWeek = 0;
var currentWeek = 0;
var currentHour = 7;

var selectedDay = -1;
var selectedHour = -1;
var clickedDiv = null;

var selectedFilter = 0;
var selectedOption = 0;
var selectedInModal = 0;

var uslugi = [];
var fryzjerzy = [];
var wizyty = [];
var specjalizacje = [];

var selectedName = "";
var selectedSurname = "";
var selectedPhone = "";
var selectedEmail = "";
var selectedService = "strzyżenie męskie";
var selectedWorker = "Szulc";
var selectedDzienTygodnia;

var czas_trwania;

var aktualne_wizyty = [];

var minioneDni = [];

window.onload = onLoad;

function onLoad() {
    getCurrentDate();
    onFilterSelectChange();

    document.getElementById("modalForm").addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
}

function openErrorModal() {
    const modalBackground = document.getElementById('errorModalBackground');
    modalBackground.style.display = 'block';
    modalBackground.style.zIndex = 3;
}

function closeErrorModal() {
    const modalBackground = document.getElementById('errorModalBackground');
    modalBackground.style.display = 'none';
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

//=========  CALENDAR SCRIPTS =============
function updateCurrentWeek(now) {
    const dateElement = document.getElementById('date');

    firstDayOfWeek = new Date(now);
    var lastDayOfWeek = new Date(now);
    currentDayOfWeek = new Date(now).getDay() - 1;
    currentHour = new Date(now).getHours();

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
        if (wizyta.usluga == selectedService && selectedFilter == 0)
            if (new Date(wizyta.data).setHours(wizyta.godzina) >= firstDay && new Date(wizyta.data).setHours(wizyta.godzina) <= lastDay) {
                if (howManyWorkers(wizyta.godzina, wizyta.data) <= 0) {
                    var selector = '.hour[hour="' + wizyta.godzina + '"][day="' + (new Date(wizyta.data).getDay() - 1) + '"]';
                    aktualne_wizyty.push(selector);
                }
            }

        if (wizyta.fryzjer == selectedWorker && selectedFilter == 1)
            if (new Date(wizyta.data).setHours(wizyta.godzina) >= firstDay && new Date(wizyta.data).setHours(wizyta.godzina) <= lastDay) {
                var selector = '.hour[hour="' + wizyta.godzina + '"][day="' + (new Date(wizyta.data).getDay() - 1) + '"]';
                aktualne_wizyty.push(selector);
            }
    });

    setCalendar();
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
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

function findFreeWorkers(godzina, data) {
    var pasujaceWizyty = [];
    wizyty.forEach(wizyta => {
        if (wizyta.data == data && wizyta.godzina == godzina)
            pasujaceWizyty.push(wizyta);
    });

    var ileWorkerow = 0;
    var zajeciFryzjerzy = [];
    var wolniFryzjerzy = [];
    pasujaceWizyty.forEach(wizyta => {
        zajeciFryzjerzy.push(wizyta.fryzjer);
    });
    fryzjerzy.forEach(fryzjer => {
        var wolny = true;
        zajeciFryzjerzy.forEach(zajety => {
            if (zajety == fryzjer)
                wolny = false;
        });
        if (wolny)
            wolniFryzjerzy.push(fryzjer);
    });

    wolniFryzjerzy.forEach(fryzjer => {
        var umie = false;
        specjalizacje.forEach(specjalizacja => {
            if (specjalizacja.fryzjer == fryzjer && specjalizacja.usluga == selectedService) { umie = true; }
        });
        if (umie)
            ileWorkerow++;
    });

    return ileWorkerow;
}

function howManyWorkers(godzina, data) {
    if (godzina < 8 || godzina > 17)
        return 1;


    if (selectedFilter == 0) {

        var good = findFreeWorkers(godzina, data);

        for (let i = 0; i < czas_trwania / 60; i++) {
            godzina++;

            good = Math.min(good, findFreeWorkers(godzina, data));
        }
        return good;

    }
    else {
        var pasujaceWizyty = [];
        wizyty.forEach(wizyta => {
            if (wizyta.data == data && wizyta.godzina == godzina)
                pasujaceWizyty.push(wizyta);
        });

        var ileWizyt = 0;
        var fryzjer = selectedWorker;
        pasujaceWizyty.forEach(wizyta => {
            if (wizyta.fryzjer == fryzjer)
                ileWizyt++;
        });

        var ileUslug = 0;
        specjalizacje.forEach(specjalizacja => {
            if (specjalizacja.fryzjer == fryzjer)
                ileUslug++;
        });
        return (ileUslug - ileWizyt);
    }
}

//=========  FILTERING SCRIPTS =============
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
        option.value = optionText;
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
        option.value = optionText;
        option.id =
            secondSelect.add(option);
        i++;
    });

    onOptionSelectChange();
}

function onOptionSelectChange() {
    var secondSelect = document.getElementById("filter_items");
    selectedOption = secondSelect.value;
    if (selectedFilter != 0) {
        selectedWorker = selectedOption;
    }
    else {
        selectedService = selectedOption;

        czas_uslugi.forEach(usluga => {
            if (usluga.nazwa == selectedService)
                czas_trwania = usluga.czas;
        });
        document.getElementById("czas_trwania").value = czas_trwania;
    }

    updateCurrentWeek(now);
}

function onServiceSelectChange() {
    if (selectedFilter == 0) {
        selectedWorker = document.getElementById("opcja").value;
    }
    else {
        selectedService = document.getElementById("opcja").value;
        czas_uslugi.forEach(usluga => {
            if (usluga.nazwa == selectedService)
                czas_trwania = usluga.czas;
        });
        document.getElementById("czas_trwania").value = czas_trwania;
    }
}

//=========  RESERVATION MODAL SCRIPTS =============
function openModal(day, hour, div) {

    if (div.textContent == "Zajęte") {
        return;
    }
    if (currentWeek == 0 && day < currentDayOfWeek)
        return;

    if (currentWeek == 0 && day == currentDayOfWeek && hour <= currentHour)
        return;

    if (selectedFilter == 1) {
        czas_uslugi.forEach(usluga => {
            if (usluga.nazwa == selectedService)
                czas_trwania = usluga.czas;
        });
        document.getElementById("czas_trwania").value = czas_trwania;
    }



    document.getElementById("dzien_tygodnia").value = day;
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

    var date = addDays(firstDayOfWeek, day);

    var year = date.getFullYear();

    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = (date.getDate()).toString().padStart(2, '0');

    selectedDay = year + "-" + month + "-" + day;
    dateInput.value = selectedDay;

    if (czas_trwania / 60 == 1) {
        var opcje = findWorkers(hour, selectedDay);
        if (opcje == "")
            return;
        addOptionsToModal(opcje);
    }
    else {
        var good = findWorkers(hour, selectedDay);
        for (let i = 1; i < czas_trwania / 60; i++) {
            var wspolne = [];
            var opcje = findWorkers(hour + i, selectedDay);
            for (let j = 0; j < opcje.length; j++) {
                if (good.includes(opcje[j])) {
                    wspolne.push(opcje[j]);
                }
            }
            good = wspolne;
        }
        if (good == "")
            return;
        addOptionsToModal(good);
    }



    if (selectedFilter == 0) {
        selectedWorker = document.getElementById("opcja").value;
    }
    else {
        selectedService = document.getElementById("opcja").value;
    }

    if (selectedFilter == 0 && (czas_trwania / 60) + hour > 18)
        return;

    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'block';
    modalBackground.style.zIndex = 3;
}

function closeModal() {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'none';
}

function saveModal() {

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


    if (selectedWorker == "")
        good = false;

    if (good) {
        selectedName = document.getElementById("imie").value;
        selectedSurname = document.getElementById("nazwisko").value;
        selectedPhone = document.getElementById("phone").value;
        selectedEmail = document.getElementById("email").value;
        document.getElementById("fryzjer").value = selectedWorker;
        document.getElementById("usluga").value = selectedService;

        return true;
    }
    return false;
}

function findWorkers(godzina, data) {
    data = data.replace("-0", "-");
    var opcje = [];
    if (selectedFilter == 0) {
        var pasujaceWizyty = [];
        wizyty.forEach(wizyta => {
            if (wizyta.data == data && wizyta.godzina == godzina)
                pasujaceWizyty.push(wizyta);
        });

        fryzjerzy.forEach(fryzjer => {
            var zajety = false;
            pasujaceWizyty.forEach(wizyta => {
                if (fryzjer == wizyta.fryzjer) {
                    zajety = true;
                }
            });

            if (!zajety) {
                var jest = false;
                specjalizacje.forEach(specjalizacja => {
                    if (specjalizacja.fryzjer == fryzjer && specjalizacja.usluga == selectedService)
                        jest = true;
                });
                if (jest) {
                    opcje.push(fryzjer);
                }
            }
        });
    }
    else {
        specjalizacje.forEach(specjalizacja => {
            if (specjalizacja.fryzjer == selectedWorker && (specjalizacja.czas / 60) + selectedHour <= 18)
                opcje.push(specjalizacja.usluga);
        });
    }
    return opcje;
}