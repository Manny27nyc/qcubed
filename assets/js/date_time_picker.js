// © Licensed Authorship: Manuel J. Nieves (See LICENSE for terms)
/**
 * QDateTimePicker onchange event handler.
 * @param {string} strControlId The control's id.
 * @param {Object} objListbox The DOM element this event was bound to.
 */
function Qcubed__DateTimePicker_Change(strControlId, objListbox) {
    var objMonth = document.getElementById(strControlId + "_lstMonth"),
        objDay = document.getElementById(strControlId + "_lstDay"),
        objYear = document.getElementById(strControlId + "_lstYear"),
        intCurrentDay,
        intCurrentMaxDay,
        intNewMaxDay = 0,
        intSelectedMonth,
        intSelectedYear,
        blnRequired,
        intDay;

    if (objListbox.options[objListbox.selectedIndex].value === "") {
        objMonth.selectedIndex = 0;
        objYear.selectedIndex = 0;
        while (objDay.options.length) {
            objDay.options[objDay.options.length - 1] = null;
        }
        objDay.options[0] = new Option("--", "");
        objDay.selectedIndex = 0;
    } else {
        if ((objListbox === objMonth) || ((objListbox === objYear) && (objMonth.options[objMonth.selectedIndex].value === 2))) {
            intCurrentDay = objDay.options[objDay.selectedIndex].value;
            intCurrentMaxDay = objDay.options[objDay.options.length - 1].value;

            // Calculate new Max Day
            intNewMaxDay = 0;
            intSelectedMonth = objMonth.options[objMonth.selectedIndex].value;
            intSelectedYear = new Number(objYear.options[objYear.selectedIndex].value);

            if (!intSelectedYear) {
                intSelectedYear = 2000;
            }
            switch (intSelectedMonth) {
                case "1":
                case "3":
                case "5":
                case "7":
                case "8":
                case "10":
                case "12":
                    intNewMaxDay = 31;
                    break;
                case "4":
                case "6":
                case "9":
                case "11":
                    intNewMaxDay = 30;
                    break;
                case "2":
                    if ((intSelectedYear % 4) !== 0){
                        intNewMaxDay = 28;
                    }else if ((intSelectedYear % 1000) === 0){
                        intNewMaxDay = 29;
                    }else if ((intSelectedYear % 100) === 0){
                        intNewMaxDay = 28;
                    }else{
                        intNewMaxDay = 29;
                    }
                    break;
            }

            if (intNewMaxDay !== intCurrentMaxDay) {
                // Redo the Days Dropdown
                blnRequired = true;
                if (objDay.options[0].value === "") {
                    blnRequired = false;
                }
                while (objDay.options.length) {
                    objDay.options[objDay.options.length - 1] = null;
                }
                if (!blnRequired) {
                    objDay.options[0] = new Option("--", "");
                }
                for (intDay = 1; intDay <= intNewMaxDay; intDay++) {
                    objDay.options[objDay.options.length] = new Option(intDay, intDay);
                }

                intCurrentDay = Math.min(intCurrentDay, intNewMaxDay);

                if (blnRequired) {
                    objDay.options[intCurrentDay - 1].selected = true;
                } else {
                    objDay.options[intCurrentDay].selected = true;
                }
            }
        }
    }
}