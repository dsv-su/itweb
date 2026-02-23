import 'flowbite';
/*import 'flowbite/dist/datepicker';*/
import Datepicker from "flowbite-datepicker/Datepicker";
const el = document.getElementById("datepickerId");
if (el) new Datepicker(el, { /* options */ });
import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';
window.Chart = Chart;


