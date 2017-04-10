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
    var fields = form.querySelectorAll("input:not([type=submit]), textarea, select");
    var data = "";
    var i = 0;
    [].forEach.call(fields,function(field){
        if(field.type == 'checkbox'){
            if(field.checked)
                data += field.name+"="+"true";
            else
                data += field.name+"="+"false";
        }

        else
            data += field.name+"="+field.value;
        if(i++ < fields.length-1)
            data +="&";
    });
    return data
};

(function(){
    // DYNAMIC TABLES
    var addDeleteTableRow = function(e){
        if(e.target.dataset.action == 'add'){
            e.preventDefault();
            var currentTable = e.target.parentNode.parentNode.parentNode.parentNode;
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
            });
            currentTableBody.appendChild(cln);
        }
        if(e.target.dataset.action == 'delete'){
            e.preventDefault();
            var currentRow = e.target.parentNode.parentNode;
            currentRow.parentNode.removeChild(currentRow);
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