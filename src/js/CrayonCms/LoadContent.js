import Promise from 'babel-runtime/core-js/promise';

export default class LoadContent {

  constructor(params){
    this.bodyIsLoaded = params.bodyIsLoaded ? 'true' : 'false';
    this.ajaxLinks = document.getElementsByClassName(params.ajaxLinksClass);
    this.contentContainer = document.getElementById(params.contentContainerId);
    this.beforeContent = document.getElementById(params.beforeContentId);
    this.listenAjaxLinks();
    this.listenPopState();
  }

  listenAjaxLinks(){
    for(let link in this.ajaxLinks){
      if (this.ajaxLinks.hasOwnProperty(link)){
        this.ajaxLinks[link].addEventListener('click', this.loadPage.bind(this));
      }
    }
  }

  listenPopState(){
    window.addEventListener('popstate', (e) => {
      if(e.state !== null) {
        this.fetchPage(e.state.url);
        this.setDocumentTitle(e.state.title);
      }
      else window.history.back();
    });
  }

  loadPage(e){
    e.preventDefault();
    this.fetchPage(e.target.href);
    this.setDocumentTitle(e.target.title);
    this.phushHistoryState(e.target);
  }

  fetchPage(url){
    fetch(this.prepareUrl(url), {headers: this.setHeaders()})
      .then(response => this.checkResponseStatus(response))
      .then(response => response.json())
      .then(json => {
        this.appendResult(json);
      })
      .catch(error => {this.appendResult({"content": `<h1 class="not-found">${error.message}</h1>`})});
  }

  checkResponseStatus(response){
    return new Promise((resolve, reject) => {
      if (response.status === 404) {
        reject(new Error(`404: ${response.statusText}`))
      } else {
        resolve(response);
      }
    });
  }

  appendResult(json){
    this.contentContainer.innerHTML = json.content || "";
    this.beforeContent.innerHTML = json.contentTitle || "";
  }

  setHeaders(){
    return new Headers({'X-Requested-With': 'XMLHttpRequest'})
  }

  prepareUrl(url){
    return url+"?ajax="+this.bodyIsLoaded
  }


  phushHistoryState(target){
    var targetUrl = target.href;
    var targetTitle = (target.title) ? target.title : target.innerText;
    window.history.pushState({url: "" + targetUrl + "",title: "" + targetTitle + ""}, targetTitle, targetUrl);
  }

  setDocumentTitle(title){
    var documentTitle = document.title;
    documentTitle = documentTitle.split(" | ");
    document.title = documentTitle[0]+" | "+title;
  };

}