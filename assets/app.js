// Import Bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

// Import Chart.js
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// start the Stimulus application
import "./bootstrap";

import { Application } from "stimulus";
import { definitionsFromContext } from "stimulus/webpack-helpers";

let application;

function startStimulusApp() {
  if (application) {
    console.log("application #stopping");
    application.stop();
    console.log("application #stopped");
  }

  application = Application.start();
  const context = require.context("./controllers", true, /\.js$/);
  application.load(definitionsFromContext(context));

  console.log("application #starting");
  application.start();
  console.log("application #start");
}

// Démarrer Stimulus initialement
startStimulusApp();

// Exporter la fonction pour redémarrer Stimulus
export { startStimulusApp };
