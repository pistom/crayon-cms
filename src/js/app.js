import LoadContent from './CrayonCms/LoadContent';
import ContactForm from './CrayonCms/ContactForm';

new LoadContent({
  bodyIsLoaded: true,
  ajaxLinksClass: 'ajaxLink',
  contentContainerId: 'mainContent',
  beforeContentId: 'mainContentTitle'
});

new ContactForm({
  contactFormId: 'mainContactForm'
});