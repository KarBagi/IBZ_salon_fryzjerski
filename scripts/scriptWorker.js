var now;
var firstDayOfWeek;
var currentWeek = 0;

var selectedDay = -1;
var selectedHour = -1;

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

var aktualne_zatwierdzone_wizyty = [];
var aktualne_niezatwierdzone_wizyty = [];

window.onload = onLoad;

function onLoad() {
    getCurrentDate();

    document.getElementById("modalForm").addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    showWorkers();
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

        if (wizyta.fryzjer == selectedWorker)
            if (new Date(wizyta.data).setHours(wizyta.godzina) >= firstDay && new Date(wizyta.data).setHours(wizyta.godzina) <= lastDay) {
                var selector = '.hour[hour="' + wizyta.godzina + '"][day="' + (new Date(wizyta.data).getDay() - 1) + '"]';
                if (wizyta.zatwierdzona==1)
                    aktualne_zatwierdzone_wizyty.push(selector);
                else
                    aktualne_niezatwierdzone_wizyty.push(selector);
            }
    });

    setCalendar();
}

function clearCalendar() {
    if (aktualne_zatwierdzone_wizyty != null) {
        aktualne_zatwierdzone_wizyty.forEach(selector => {
            var divWithAttributes = document.querySelector(selector);
            divWithAttributes.style.backgroundColor = "#fff";
            divWithAttributes.textContent = "Wolne";
        });
    }
    aktualne_zatwierdzone_wizyty = [];

    if (aktualne_niezatwierdzone_wizyty != null) {
        aktualne_niezatwierdzone_wizyty.forEach(selector => {
            var divWithAttributes = document.querySelector(selector);
            divWithAttributes.style.backgroundColor = "#fff";
            divWithAttributes.textContent = "Wolne";
        });
    }
    aktualne_niezatwierdzone_wizyty = [];

}

function setCalendar() {
    if (aktualne_zatwierdzone_wizyty != null) {
        aktualne_zatwierdzone_wizyty.forEach(selector => {
            var divWithAttributes = document.querySelector(selector);
            divWithAttributes.style.backgroundColor = "#30b521";
            divWithAttributes.textContent = "Zatwierdzone";
        });
    }

    if (aktualne_niezatwierdzone_wizyty != null) {
        aktualne_niezatwierdzone_wizyty.forEach(selector => {
            var divWithAttributes = document.querySelector(selector);
            divWithAttributes.style.backgroundColor = "#c2880c";
            divWithAttributes.textContent = "Niezatwierdzone";
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

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

//=========  MODAL SCRIPTS =============
function openModal(day, hour) {

    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'block';
    modalBackground.style.zIndex = 3;

    var date = addDays(firstDayOfWeek, day);
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = (date.getDate()).toString().padStart(2, '0');
    selectedDay = year + "-" + month + "-" + day;

    var hours = hour;
    hours = hours < 10 ? "0" + hours : hours;
    document.getElementById("czas").value = hours + ":00";
    document.getElementById("termin").value = selectedDay; 
    document.getElementById("opcja_fryzjer").value = "";
    document.getElementById("opcja").value = "";
    document.getElementById("imie").value = "";
    document.getElementById("nazwisko").value = "";
    document.getElementById("zatwierdzono").value = "";
    document.getElementById("phone").value = "";
    document.getElementById("email").value = "";
    document.getElementById("id_wizyty").value = "";

    var wyswietlana_wizyta = [];
    wizyty.forEach(wizyta => {
        if (wizyta.data == selectedDay && wizyta.godzina == hour && wizyta.fryzjer == selectedWorker)
            wyswietlana_wizyta = wizyta;
    });

    if (wyswietlana_wizyta=="")
        return;

    document.getElementById("opcja_fryzjer").value = wyswietlana_wizyta.fryzjer;
    document.getElementById("opcja").value = wyswietlana_wizyta.usluga;
    document.getElementById("imie").value = wyswietlana_wizyta.klient_imie;
    document.getElementById("nazwisko").value = wyswietlana_wizyta.klient_nazwisko;

    if (wyswietlana_wizyta.zatwierdzona==1)
        document.getElementById("zatwierdzono").value = "tak";
    else
        document.getElementById("zatwierdzono").value = "nie";

    if (wyswietlana_wizyta.numer_telefonu != "")
        document.getElementById("phone").value = wyswietlana_wizyta.numer_telefonu;

    if (wyswietlana_wizyta.email != "")
        document.getElementById("email").value = wyswietlana_wizyta.email;

        document.getElementById("id_wizyty").value = wyswietlana_wizyta.id_wizyty;
}

function closeModal() {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'none';
}


//=========  FILTERING SCRIPTS =============

function showWorkers() {
    var workerSelect = document.getElementById("calendar_filter");
    
    fryzjerzy.forEach(fryzjer => {
        var option = document.createElement("option");
        option.text = fryzjer;
        option.value = fryzjer;
        workerSelect.add(option);
    });

    changeWorker();
}

function changeWorker() {
    var workerSelect = document.getElementById("calendar_filter");
    selectedWorker = workerSelect.value;
    updateCurrentWeek(now);
}
