export default class ContactForm {
  constructor(params) {
    this.contactFormId = params.contactFormId;
    this.listenContactForm();
    this.observeIfContactFormPageIsLoaded();
  }

  listenContactForm() {
    const contactForm = document.getElementById(this.contactFormId);
    contactForm ? contactForm.addEventListener('submit', this.submitForm) : null;
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
            contactForm.removeEventListener('submit', this.submitForm);
          }
        });
        Object.keys(addedNodes).map((node) => {
          if (addedNodes[node].id === this.contactFormId) {
            contactForm = addedNodes[node];
            contactForm.addEventListener('submit', this.submitForm);
          }
        });
      });
    });
    observer.observe(document, { subtree: true, childList: true });
  }

  submitForm(e) {
    e.preventDefault();
    var form = new FormData(e.target);
    fetch(e.target.action, {
      method: "POST",
      body: form
    });
  }
}
