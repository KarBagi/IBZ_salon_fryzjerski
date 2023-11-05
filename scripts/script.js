var now;

function updateCurrentWeek(now) {
    const dateElement = document.getElementById('date');

    const firstDayOfWeek = new Date(now);
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