var now;
var firstDayOfWeek;
var currentWeek = 0;

var selectedDay = -1;
var selectedHour = -1;
var clickedDiv = null;

var wizyty = [
    { data: "2023-11-10", godzina: 10 },
    { data: "2023-11-8", godzina: 15 },
    { data: "2023-11-15", godzina: 14 },
    { data: "2023-1-12", godzina: 10 },
    { data: "2023-11-30", godzina: 14 },
    { data: "2023-12-1", godzina: 10 },
    { data: "2023-11-20", godzina: 16 }
];

aktualne_wizyty = [];

function updateCurrentWeek(now) {
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


window.onload = getCurrentDate;

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
    if (div.textContent == "Zajęte") {
        return;
    }

    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'block';
    modalBackground.style.zIndex = 3;

    selectedDay = day;
    selectedHour = hour;
    clickedDiv = div;

    var timeInput = document.getElementById("czas");

    var hours = hour;
    var minutes = 0;

    // Dodaj zero z przodu, jeśli liczba minut jest jednocyfrowa
    minutes = minutes < 10 ? "0" + minutes : minutes;
    hours = hours < 10 ? "0" + hours : hours;
    // Ustaw godzinę i minutę w polu input
    timeInput.value = hours + ":" + minutes;

    var dateInput = document.getElementById("termin");
    var date = addDays(firstDayOfWeek, day);

    // Ustaw wartość daty w formie tekstu (YYYY-MM-DD)

    var year = date.getFullYear();

    // Dodaj zero z przodu, jeśli miesiąc lub dzień jest jednocyfrowy
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = (date.getDate()).toString().padStart(2, '0');

    // Ustaw datę w polu input
    selectedDay = year + "-" + month + "-" + day;
    dateInput.value = selectedDay;

}

function closeModal() {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'none';
    document.getElementById("dropbutton").textContent = "Wybierz";
}

function saveModal() {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'none';
    document.getElementById("dropbutton").textContent = "Wybierz";
    clickedDiv.style.backgroundColor = "#b52121";
    clickedDiv.textContent = "Zajęte";
    wizyty.push({ data: selectedDay, godzina: selectedHour });
    var selector = '.hour[hour="' + selectedHour + '"][day="' + new Date(selectedDay).getDay() + '"]';
    aktualne_wizyty.push(selector);
    updateCurrentWeek(firstDayOfWeek);
}

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function setService(service) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
    }

    if (service == 1) { document.getElementById("dropbutton").textContent = "strzyżenie męskie" }

    if (service == 2) { document.getElementById("dropbutton").textContent = "strzyżenie damskie" }

    if (service == 3) { document.getElementById("dropbutton").textContent = "farbowanie" }

}