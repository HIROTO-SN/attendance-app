import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("livewire:init", () => {

    let fp = flatpickr("#date", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            Livewire.dispatch("setTargetDate", { date: dateStr });
        }
    });

    Livewire.on("updateAllowedDates", (data) => {
        let dates = data.dates;
        if (dates.length > 0) {
            fp.set("enable", dates);
        } else {
            fp.set("enable", []);
        }
    });
});