var appointments = {};

//Relationship variables
var months = ["January", "Febuary", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var days = ["Sun","Mon","Tue","Wed","Thu","Fri", "Sat"];

//Date variables
var currentDate = new Date();
var currentYear = currentDate.getFullYear();
var currentMonth = currentDate.getMonth();

//Binding Variables
var monthElement = document.getElementById("current-month");
var yearElement = document.getElementById("current-year");
var lastMonthButton = document.getElementById("next-month");
var nextMonthButton = document.getElementById("last-month");
var calendar = document.getElementById('calendar-table')
var appointmentDate = document.getElementById('appointment-date');
var appointmentTitle = document.getElementById('appointment-title');
var deleteButton = document.getElementById('delete-appointment');
var saveButton = document.getElementById('save-appointment');
var tags = document.getElementsByTagName('td')

//Allow past date - i created that variable because the description says current day and future date, not past
var allowPastDate = false;

//Functions
function calculateFirstAndLastDayOfMonth() {
    return { 
                firstDay: new Date(currentYear, currentMonth, 1),
                lastDay: new Date(currentYear, currentMonth+1, 0) 
            };
}

function fillCalendar() {
    var lines = document.getElementById('content-dates').children;
    
    var days = calculateFirstAndLastDayOfMonth();
    
    var iterationDate = days.firstDay.getDate();
    var firstDayOfWeek = days.firstDay.getUTCDay();
    var lastDay = days.lastDay.getDate();
    
    for ( var line = 0; line < lines.length; line++ ) {
        lines[line].style.display = "table-row";
        for ( i = 0; i < lines[line].children.length; i++ ) {
                lines[line].children[i].style.backgroundColor = "";
            if ( line == 0 && i < firstDayOfWeek  ) {
                lines[line].children[i].innerText = " "
                continue;
            } else if (iterationDate > lastDay) {
                lines[line].children[i].innerText = " "
                if ( i == 0 && line < lines.length ) {
                  lines[line].style.display = "none";
                }
                continue;
            }
            
            if ( appointments[currentYear] !== undefined && 
                appointments[currentYear][currentMonth+1] !== undefined && 
                appointments[currentYear][currentMonth+1][iterationDate] !== undefined ) {
                lines[line].children[i].style.backgroundColor = "#FDD";
            }
            
            lines[line].children[i].innerText = iterationDate++;
        }
    }
}

function changeCurrentMonth(value = 1) {
    currentMonth += value;
    if ( currentMonth < 0 ) {
        currentMonth = 11;
        currentYear -= 1;
    } else if ( currentMonth > 11 ) {
        currentMonth = 0;
        currentYear += 1;
    }
    
    yearElement.innerText = currentYear;
    monthElement.innerText = months[ currentMonth ];
    fillCalendar();    
}

function saveAppointment() {
    var date = appointmentDate.value;
    var value = appointmentTitle.value.trim();
    setAppointment(date, value);
}

function setAppointment(stringDate, value) {
    var date = stringDate.split("/");
    if ( appointments[date[2]*1] === undefined ) {
        appointments[date[2]*1] = {};
    }
    if ( appointments[date[2]*1][date[0*1]] === undefined) {
        appointments[date[2]*1][date[0]*1] = {};
    }
    appointments[date[2]*1][date[0]*1][date[1]*1] = value;
    appointmentDate.value = "";
    appointmentTitle.value = "";
    fillCalendar();
}

function getAppointment(stringDate) {
    var date = stringDate.split("/");
    if (
        appointments[date[2]*1] !== undefined &&
        appointments[date[2]*1][date[0]*1] !== undefined &&
        appointments[date[2]*1][date[0]*1][date[1]*1] !== undefined ) {
        return appointments[date[2]*1][date[0]*1][date[1]*1];
    }
    return undefined;
}

function dateIsValid(value) {
    //TODO verify if date is really valid on calendar and if the date is not past
    console.log( new Date(2018, 10, 20) );
    if ( !value.trim() || !value.match(/^[01][0-9]\/[0-3][0-9]\/[0-9]{4}$/) ) {
        return false;
    }
    return true;
}

function showDeleteButton(date) {
    alert('That was a day with an appointment, but, you can delete by clicking on button or edit changing it\'s value and clicking save.');
    deleteButton.setAttribute("class", "btn-danger");
    appointmentTitle.value = getAppointment(date);
}

function hideDeleteButton() {
    deleteButton.setAttribute("class", "d-none btn-danger");
}
 
function showOrHideAppointmentDeletion(self) {
    if ( dateIsValid(self.value) && getAppointment(self.value) ) {
        showDeleteButton(self.value);
    } else {
        hideDeleteButton();
    }
}

//Event bindings
saveButton.onclick = function() {
    var errors = [];
    if ( !appointmentTitle.value.trim() ) {
        errors.push('The title is required.');
    }
    if ( !dateIsValid(appointmentDate.value) ) {
        errors.push('The date is blank or invalid (format should be mm/dd/yyyy).');
    }
    
    if ( errors.length ) {
        alert( errors.join("\n"));
    } else {
        saveAppointment();
        hideDeleteButton();
    }
}

nextMonthButton.onclick = function() {
    changeCurrentMonth(-1);
}

lastMonthButton.onclick = function() {
    changeCurrentMonth();
}

appointmentDate.onkeyup = function() {
    showOrHideAppointmentDeletion(this);
}

deleteButton.onclick = function() {
    var currentDate = appointmentDate.value
    if ( confirm("Are you sure to delete this appointment?") ) {
        hideDeleteButton();
        setAppointment(currentDate);
        appointmentDate.value = "";
        appointmentTitle.value = "";
    }
}

//Calling page load calendar fill
fillCalendar();

//Add event triggers for all days on calendar
for ( i = 0; i < tags.length; i++ ) {
    var tag = tags[i];
    tags[i].onclick = function() {
        if ( this.innerText != "" ) {
            var day = "00".substring(this.innerText.length)+this.innerText;
            var month = "00".substring((""+(currentMonth+1)).length)+(currentMonth+1);
            var date =  month + "/" + day + "/" + currentYear;
            if ( getAppointment(date) ) {
                showDeleteButton(date);
            }
            appointmentDate.value = date;
            appointmentTitle.focus();
        }
        
    }
}