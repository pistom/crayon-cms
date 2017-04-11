(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(factory);
  } else if (typeof exports === 'object') {
    module.exports = factory;
  } else {
    root.atomic = factory(root);
  }
})(this, function (root) {

  'use strict';

  var exports = {};

  var config = {
    contentType: 'application/x-www-form-urlencoded'
  };

  var parse = function (req) {
    var result;
    try {
      result = JSON.parse(req.responseText);
    } catch (e) {
      result = req.responseText;
    }
    return [result, req];
  };

  var xhr = function (type, url, data) {
    var methods = {
      success: function () {},
      error: function () {},
      always: function () {}
    };
    var XHR = root.XMLHttpRequest || ActiveXObject;
    var request = new XHR('MSXML2.XMLHTTP.3.0');

    request.open(type, url, true);
    request.setRequestHeader('Content-type', config.contentType);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.onreadystatechange = function () {
      var req;
      if (request.readyState === 4) {
        req = parse(request);
        if (request.status >= 200 && request.status < 300) {
          methods.success.apply(methods, req);
        } else {
          methods.error.apply(methods, req);
        }
        methods.always.apply(methods, req);
      }
    };
    request.send(data);

    var atomXHR = {
      success: function (callback) {
        methods.success = callback;
        return atomXHR;
      },
      error: function (callback) {
        methods.error = callback;
        return atomXHR;
      },
      always: function (callback) {
        methods.always = callback;
        return atomXHR;
      }
    };

    return atomXHR;
  };

  exports.get = function (src) {
    return xhr('GET', src);
  };

  exports.put = function (url, data) {
    return xhr('PUT', url, data);
  };

  exports.post= function (url, data) {
    return xhr('POST', url, data);
  };

  exports.delete = function (url) {
    return xhr('DELETE', url);
  };

  exports.setContentType = function(value) {
    config.contentType = value;
  };

  return exports;

});

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}
// Navigation
(function(){
    var navigation = document.getElementsByClassName('mainNavigation')[0];
    var navigationLinks = navigation.getElementsByTagName('a');

    var mainContent = document.getElementsByClassName('mainContent')[0];
    if(mainContent === undefined)
        console.log("No content block found. Add block classed 'mainContent' to your template.");

    var setMenuItemAsActive = function(item){
        Array.prototype.forEach.call(navigationLinks,function(i){
          i.parentNode.classList.remove("active");
        });
        if(item.tagName === "A"){
            Array.prototype.forEach.call(navigationLinks,function(i){
                console.log(i.href+" "+item.href);
                if(i.href == item.href)
                    i.parentNode.classList.add("active");
            });
        }
        else if(typeof item === 'string') {
            var i = navigation.querySelector("a[title='"+item+"']");
            i.parentNode.classList.add("active");
        }
    };
    var setDocumentTitle = function(title){
        var documentTitle = document.title;
        documentTitle = documentTitle.split(" | ");
        document.title = documentTitle[0]+" | "+title;
    };

    var setURL = function(item){
        var targetUrl = item.href;
        var targetTitle = (item.title) ? item.title : item.innerText;
        setDocumentTitle(targetTitle);
        window.history.pushState({url: "" + targetUrl + "",title: "" + targetTitle + ""}, targetTitle, targetUrl);
    };

    var getContent = function(url){
        atomic.get(url)
            .success(function(data,xhr){
                mainContent.innerHTML = data;
            })
            .error(function(){
                mainContent.innerHTML = "Page not found";
            });
    };

    var getContentPage = function(e){
        var contentURL = "";
        if(e.target !== undefined && e.target.tagName === 'A' && e.target.dataset.ajax == 'true'){
            e.preventDefault();
            contentURL = e.target.href;
            setURL(e.target);
            setMenuItemAsActive(e.target);
            getContent(contentURL)
        } else if(e.title !== undefined) {
            contentURL = e.url;
            setMenuItemAsActive(e.title);
            setDocumentTitle(e.title);
            getContent(contentURL)
        }

    };
    setURL(navigation.getElementsByClassName("active")[0].getElementsByTagName("a")[0]);

    window.addEventListener('popstate', function(e) {
        if(e.state !== null) getContentPage(e.state);
        else window.history.back();
    });

    if(mainContent){
        document.body.addEventListener("click",getContentPage,false);
    }

})();