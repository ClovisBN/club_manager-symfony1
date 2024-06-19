// assets/app.js
import "./styles/app.css";

// Import Chart.js
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

console.log("Chart.js is ready to use!");
