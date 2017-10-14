import Validate from 'validate.js';

export default class ContactForm {
  constructor(params) {
    this.contactFormId = params.contactFormId;
    this.contactForm = document.getElementById(this.contactFormId);
    this.listenContactForm();
    this.observeIfContactFormPageIsLoaded();
    this.messagesOutput = params.messagesOutput;
    this.translate = params.translations;
  }

  listenContactForm() {
    this.contactForm ? this.contactForm.addEventListener('submit', (e) => this.submitForm(e)) : null;
  }

  observeIfContactFormPageIsLoaded() {
    const MO = window.MutationObserver || window.WebKitMutationObserver;
    const observer = new MO((mutations) => {
      Object.keys(mutations).map((mutation) => {
        let contactForm;
        const addedNodes = mutations[mutation].addedNodes;
        const removedNodes = mutations[mutation].removedNodes;
        Object.keys(removedNodes).map((node) => {
          if (removedNodes[node].id === this.contactFormId) {
            contactForm = removedNodes[node];
            contactForm.removeEventListener('submit', (e) => this.submitForm(e));
          }
        });
        Object.keys(addedNodes).map((node) => {
          if (addedNodes[node].id === this.contactFormId) {
            contactForm = addedNodes[node];
            contactForm.addEventListener('submit', (e) => this.submitForm(e));
          }
        });
      });
    });
    observer.observe(document, { subtree: true, childList: true });
  }

  submitForm(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    if (this.validateForm(e.target)) {
      const messageContent = document.createTextNode('...');
      this.messagesOutput.showMessage('info', this.translate.sending || "Sending", messageContent);

      fetch(e.target.action, {method: "POST", body: formData})
        .then(response => this.checkResponseStatus(response))
        .then(response => response.json())
        .then(json => {
          const messageContent = document.createTextNode(json.message);
          const messageTitle = json.status === 'success' ?
            this.translate.message_sent || "Your message has been sent" :
            this.translate.error || "Error";
          this.messagesOutput.showMessage(json.status, messageTitle || "Error", messageContent);
          json.status === 'success' ? this.contactForm.reset() : null;
        })
        .catch(error => {
          const errorContent = document.createTextNode(error.message);
          this.messagesOutput.showMessage('error', this.translate.error || "Error", errorContent)
        })
    }
  }

  checkResponseStatus (response) {
    return new Promise((resolve, reject) => {
      if (response.status === 404) {
        reject(new Error(`404: ${response.statusText}`))
      } else {
        resolve(response)
      }
    })
  }

  validateForm(form) {
    let errors = Validate(form, this.getConstraints());
    this.clearMessages();
    if (errors) {
      this.displayErrors(errors);
      return false;
    } else {
      return true;
    }
  }

  getConstraints() {
    return {
      email: {
        presence: {message: "^" + (this.translate.email_not_blank || "Email can't be empty.")},
        email: {message: "^" + (this.translate.email_not_valid || "Email doesn't look like a valid email.")},
      },
      message: {
        presence: {message: "^" + (this.translate.message_not_blank || "Message can't be empty.")},
        length: {minimum: 4, message: "^" + (this.translate.message_too_short || "Message is too short")}
      }
    };
  }

  displayErrors (errors) {
    let errorsList = document.createElement('ul');
    Object.keys(errors).map((input) => {
      for (let i = 0; i < errors[input].length; i++){
        let errorElement = document.createElement('li');
        let errorMessage = document.createTextNode(errors[input]);
        errorElement.appendChild(errorMessage);
        errorsList.appendChild(errorElement);
      }
    });
    // this.messagesBlock.appendChild(errorsList);
    this.messagesOutput.showMessage("info", this.translate.info || 'Info', errorsList);
  }

  clearMessages(){
    // this.messagesBlock.innerHTML = '';
    this.messagesOutput.closeAlert();
  }
}
