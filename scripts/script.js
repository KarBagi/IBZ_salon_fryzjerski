var now;
var firstDayOfWeek;

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

    openModal(1, 8)
}

window.onload = getCurrentDate;

function getCurrentDate() {
    now = new Date();
    updateCurrentWeek(now)
}

function weekBack() {
    now.setDate(now.getDate() - 7);
    updateCurrentWeek(now);
}

function weekForward() {
    now.setDate(now.getDate() + 7);
    updateCurrentWeek(now);
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function openModal(day, hour) {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'block';
    modalBackground.style.zIndex = 3;

    var timeInput = document.getElementById("czas");

    var hours = hour;
    var minutes = 0;

    // Dodaj zero z przodu, jeśli liczba minut jest jednocyfrowa
    minutes = minutes < 10 ? "0" + minutes : minutes;
    hours = hours < 10 ? "0" + hours : hours;
    // Ustaw godzinę i minutę w polu input
    timeInput.value = hours + ":" + minutes;

    var dateInput = document.getElementById("termin");

    // Ustaw wartość daty w formie tekstu (YYYY-MM-DD)

    var year = firstDayOfWeek.getFullYear();

    // Dodaj zero z przodu, jeśli miesiąc lub dzień jest jednocyfrowy
    var month = (firstDayOfWeek.getMonth() + 1).toString().padStart(2, '0');
    var day = firstDayOfWeek.getDate().toString().padStart(2, '0');

    // Ustaw datę w polu input
    dateInput.value = year + "-" + month + "-" + day;

}

function closeModal() {
    const modalBackground = document.getElementById('modalBackground');
    modalBackground.style.display = 'none';
    document.getElementById("dropbutton").textContent = "Wybierz"
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