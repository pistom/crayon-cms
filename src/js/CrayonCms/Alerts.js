export default class Alerts {
  constructor (params) {
    this.alertBoxId = params.alertBoxId;
    this.createAlertBox();
    this.listenClose();
  }

  createAlertBox() {
    this.alertBox = document.createElement('div');
    this.alertBox.id = this.alertBoxId;
    this.closeBtn = document.createElement('span');
    this.closeBtn.classList.add('alertClose');
    this.titleBox = document.createElement('div');
    this.titleBox.classList.add('alertTitle');
    this.contentBox = document.createElement('div');
    this.contentBox.classList.add('alertContent');
    this.alertBox.appendChild(this.closeBtn);
    this.alertBox.appendChild(this.titleBox);
    this.alertBox.appendChild(this.contentBox);
    document.body.appendChild(this.alertBox);
  }

  showMessage(type, title, content){
    this.clearAlertBox();
    this.alertBox.classList.add('isOpen');
    this.alertBox.classList.add(type);
    this.titleBox.innerHTML = title;
    this.contentBox.appendChild(content);
  }

  listenClose() {
    this.closeBtn.addEventListener('click', this.closeAlert.bind(this));
  }

  closeAlert() {
    this.alertBox.classList.remove('isOpen');
    this.alertBox.classList.remove('error');
    this.alertBox.classList.remove('info');
    this.alertBox.classList.remove('success');
  }

  clearAlertBox() {
    this.titleBox.innerHTML = "";
    this.contentBox.innerHTML = "";
  }
}