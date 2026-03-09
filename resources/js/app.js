import './bootstrap';
import Swal from 'sweetalert2'
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

flatpickr("#date", {
    dateFormat: "Y-m-d",
});

window.Swal = Swal