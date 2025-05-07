import { Application } from '@hotwired/stimulus';

// Start the Stimulus application
const application = Application.start();

// Configure Stimulus development experience
application.debug = false;
window.Stimulus = application;

export { application };
