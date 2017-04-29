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
    msgWindow.className = 'msgWindow';
    msgWindow.classList.add('isOpen');
    msgWindow.classList.add(type);
    msgTitle.innerHTML = title;
    msgContent.innerHTML = msg;
};
document.body.addEventListener("click",function(e){
    if(!e.target.classList.contains("msgWindowTitle") && !e.target.classList.contains("msgWindowContent"))
        msgWindow.classList.remove('isOpen');
},false);
var closeMessage = function(msg){
    msgWindow.classList.remove('isOpen');
};
msgWindow.getElementsByClassName('msgWindowClose')[0].addEventListener('click',closeMessage,false);

// Serialize Form Data
var serializeFormData = function(form){
    var fields = form.querySelectorAll("input:not([type=submit]), textarea, select");
    var data = "";
    var i = 0;
    [].forEach.call(fields,function(field){
        if(field.type == 'checkbox'){
            if(field.checked)
                data += field.name+"="+"true";
            else
                data += field.name+"="+"false";
        } else if(field.type == 'radio'){
            if(field.checked)
                data += field.name+"="+field.value;
        }

        else
            data += field.name+"="+encodeURIComponent(field.value);
        if(i++ < fields.length-1)
            data +="&";
    });
    return data
};

var copyInput = function(src,dest,prefix,postfix){
    var newValue = '';
    if(prefix) newValue += prefix;
    newValue += src.value;
    if(postfix) newValue += postfix;
    dest.value = newValue;
};

var validateInput = function(input,pattern){
    var newValue = input.value.replace(/[^\w-]/gi, '');
    return newValue;
};

var getFileNameFromManager = function(file){
    var iframeWrapper = document.getElementById("iframeWrapper");
    var inputName = iframeWrapper.dataset.parentinput;
    iframeWrapper.parentNode.removeChild(iframeWrapper);
    var currentInput = document.getElementsByName(inputName)[0];
    currentInput.value = file;
};

(function(){
    // DYNAMIC TABLES
    var addDeleteTableRow = function(e){
        var target;
        if(e.target.nodeName == "A"){
            target = e.target;
        }
        if(e.target.nodeName == "I"){
            target = e.target.parentNode;
        }
        if(e.target.nodeName == "A" || e.target.nodeName == "I"){
            if(target.dataset.action == 'add'){
                e.preventDefault();
                var currentTable = target.parentNode.parentNode.parentNode.parentNode;
                var currentTableBody = currentTable.getElementsByTagName('tbody')[0];
                var items = currentTableBody.getElementsByTagName('tr');
                var lastItemNumber = Number(items[items.length-1].dataset.index);
                var item = items[0];
                var cln = item.cloneNode(true);
                cln.dataset.index = lastItemNumber+1;
                var newInputs = cln.getElementsByTagName('input');
                var newSelects = cln.getElementsByTagName('select');
                [].forEach.call(newInputs,function(input){
                    if(input.type == 'text')
                        input.value = "";
                    if(input.type == 'checkbox')
                        input.checked = '';
                    var oldName = input.name;
                    input.name = oldName.replace(/r\d+-/,'r'+(lastItemNumber+1)+'-');
                });
                [].forEach.call(newSelects,function(select){
                    select[0].selected = 'selected';
                    var oldName = select.name;
                    select.name = oldName.replace(/r\d+-/,'r'+(lastItemNumber+1)+'-');
                    [].forEach.call(select.getElementsByTagName('optgroup'),function(item){
                        item.style.display = 'block';
                    })
                });
                currentTableBody.appendChild(cln);
            }
            if(target.dataset.action == 'delete'){
                e.preventDefault();
                var currentRow = target.parentNode.parentNode;
                currentRow.parentNode.removeChild(currentRow);
            }
        }
    };
    document.body.addEventListener("click",addDeleteTableRow,false);
})();

(function(){
    // LOGIN FORM
    var loginForm = document.getElementById("loginForm");
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
                        },1000)
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


(function(){
    // ROUTING
    var controllersLists = document.getElementsByClassName('controllers');
    if(controllersLists){
        [].forEach.call(controllersLists,function(controllersList){
            controllersList.addEventListener('change',function(e){
                var selectedController = e.target.selectedOptions[0].value;
                var functionsIndex = e.target.dataset.loopindex;
                var functionsList = document.getElementById('functions'+functionsIndex);
                var functionsListGropus = functionsList.getElementsByTagName('optgroup');
                [].forEach.call(functionsListGropus,function(functionsListGroup){
                    var nullSelect = functionsList.getElementsByTagName('option')[0];
                    nullSelect.selected = 'selected';
                    if(functionsListGroup.dataset.optgroup == selectedController){
                        functionsListGroup.style.display = 'block';
                    }
                    else {
                        functionsListGroup.style.display = 'none';
                    }
                })
            },false);

            var selectedController = controllersList.selectedOptions[0].value;
            var functionsIndex = controllersList.dataset.loopindex;
            var functionsList = document.getElementById('functions'+functionsIndex);
            var functionsListGropus = functionsList.getElementsByTagName('optgroup');
            [].forEach.call(functionsListGropus,function(functionsListGroup){
                if(functionsListGroup.dataset.optgroup == selectedController){
                    functionsListGroup.style.display = 'block';
                }
                else {
                    functionsListGroup.style.display = 'none';
                }
            })
        })
    }



})();