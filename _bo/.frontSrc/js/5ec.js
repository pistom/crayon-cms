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