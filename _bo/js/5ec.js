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
// Page Messages
var msgWindow = document.getElementsByClassName('msgWindow')[0];
var msgTitle = msgWindow.getElementsByClassName('msgWindowTitle')[0];
var msgContent = msgWindow.getElementsByClassName('msgWindowContent')[0];
var showMessage = function(type,title,msg){
    msgWindow.classList.add('isOpen');
    msgWindow.classList.add(type);
    msgTitle.innerHTML = title;
    msgContent.innerHTML = msg;
};
var closeMessage = function(msg){
    msgWindow.classList.remove('isOpen');
};
msgWindow.getElementsByClassName('msgWindowClose')[0].addEventListener('click',closeMessage,false);

// Serialize Form Data
var serializeFormData = function(form){
    var fields = form.querySelectorAll("input:not([type=submit]), textarea");
    var data = "";
    var i = 0;
    [].forEach.call(fields,function(field){
        data += field.name+"="+field.value;
        if(i++ < fields.length-1)
            data +="&";
    });
    return data
};



(function(){
    // LOGIN FORM
    var loginForm = document.getElementById("loginForm");
    console.log(loginForm);
    if(loginForm) {
        loginForm.addEventListener("submit",function(e){
            e.preventDefault();
            console.log(this);
            var data = serializeFormData(this);
            atomic.post('/_bo/login.php',data)
                .success(function (data, xhr) {
                    console.log(data);
                    if(data.status === 'success'){
                        showMessage('success','Great!','You are logged in');
                        setTimeout(function(){
                            window.location = '/_bo/';
                        },500)
                    }
                    else{
                        showMessage('error','Error','Incorrect login data');
                    }
                })
                .error(function (data, xhr) {
                    showMessage('error','Error','Server connection error');
                });
        },false)
    }
})();