import LoadContent from './CrayonCms/LoadContent';
import ContactForm from './CrayonCms/ContactForm';
import Alerts from './CrayonCms/Alerts';

const brand = document.getElementById('brand');
brand.addEventListener('click', (e) => {
  document.location.href = e.target.dataset.url;
})

new LoadContent({
  bodyIsLoaded: true,
  ajaxLinksClass: 'ajaxLink',
  contentContainerId: 'mainContent',
  beforeContentId: 'mainContentTitle',
  loadingAnimationId: 'mainContentLoader'
});

const alertsSystem = new Alerts({
  alertBoxId: 'msgWindow'
});

new ContactForm({
  contactFormId: 'mainContactForm',
  messagesBlockId: 'mainContactFormMessage',
  messagesOutput: alertsSystem,
  translations: translate || null
});



