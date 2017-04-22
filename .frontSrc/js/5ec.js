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

//Contact form
var disableForm = function(status,form){
    var formInputs = form.querySelectorAll('input,select,textarea');
    [].forEach.call(formInputs,function(input){
        input.disabled = status;
    });
};
var initContactForm = function(){
    var mainContactForm = document.getElementById('mainContactForm');
    if(mainContactForm){
        mainContactForm.addEventListener('submit',function(e){
            e.preventDefault();
            var data = serializeFormData(e.target);
            disableForm(true,e.target);
            atomic.post(e.target.action,data)
                .success(function(data){
                    if(data.status == 'success'){
                        showMessage('success','Thank you',data.message);
                        e.target.email.value = "";
                        e.target.message.value = "";
                    }
                    if(data.status == 'error'){
                        showMessage('error','Error',data.message);
                        disableForm(false,e.target);
                    }
                })
                .error(function(data){
                    showMessage('error','Error','I can not connect to the server.');
                })
        },false);
    }
};


// Navigation
(function(){
    var brand = document.getElementsByClassName("mainHeader__brand")[0];
    brand.addEventListener("click",function(){window.location = '/'},false);
    var navigation = document.getElementById('mainNavigation');
    var navigationLinks = navigation.getElementsByTagName('a');

    var mainContent = document.getElementById('mainContent');
    var mainContentTitle = document.getElementById('mainContentTitle');
    var mainContentLoader = document.getElementById('mainContentLoader');
    if(mainContent === undefined)
        console.log("No content block found. Add block whit id 'mainContent' to your template.");

    var setMenuItemAsActive = function(item){
        Array.prototype.forEach.call(navigationLinks,function(i){
          i.parentNode.classList.remove("active");
        });
        if(item.tagName === "A"){
            Array.prototype.forEach.call(navigationLinks,function(i){
                if(i.href == item.href)
                    i.parentNode.classList.add("active");
            });
        }
        else if(typeof item === 'string') {
            var i = navigation.querySelector("a[title='"+item+"']");
            if (i)
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
    var scrollTo = function(element,scrollDuration){
        var scrollTo = element.offsetTop;
        const scrollHeight = window.scrollY,
            scrollStep = Math.PI / ( scrollDuration / 15 ),
            cosParameter = scrollHeight / 2;
        var scrollCount = 0,
            scrollMargin;
        var scrollInterval = setInterval( function() {
                if ( window.scrollY >= scrollTo ) {
                    scrollCount = scrollCount + 1;
                    scrollMargin = cosParameter - cosParameter * Math.cos( scrollCount * scrollStep );
                    window.scrollTo( 0, ( scrollHeight - scrollMargin ) );
                }
                else clearInterval(scrollInterval);
            }, 15 );
    };
    var getContent = function(url){
        var getContentOnly = (bodyIsLoaded) ? 'true' : 'false';
        atomic.get(url+"?ajax="+getContentOnly)
            .success(function(data,xhr){
                mainContent.classList.add('isHidden');
                mainContentTitle.classList.add('isHidden');
                mainContentLoader.classList.remove('isHidden');
                var mainScripts = document.getElementsByClassName('mainScripts')[0];
                setTimeout(function(){
                    mainContent.innerHTML = data.content;
                    mainContentTitle.innerHTML = (data.contentTitle) ? data.contentTitle : "";
                    document.body.className = (data.bodyClass) ? data.bodyClass : "";
                    eval(data.scripts);
                    mainContent.classList.remove('isHidden');
                    mainContentTitle.classList.remove('isHidden');
                    mainContentLoader.classList.add('isHidden');
                    scrollTo(navigation,500);
                },750);

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
    var navActiveElement = navigation.getElementsByClassName("active")[0];
    if(navActiveElement)
        setURL(navigation.getElementsByClassName("active")[0].getElementsByTagName("a")[0]);

    window.addEventListener('popstate', function(e) {
        if(e.state !== null) getContentPage(e.state);
        else window.history.back();
    });
    if(mainContent){
        document.body.addEventListener("click",getContentPage,false);
    }
    document.getElementsByClassName("mainHeader__bg")[0].drawShapes({
        shape: "oval",
        density:20,
        hideSpeed: 1000,
        showSpeed: 50,
        size: {min:4,max:4},
        speed:1000,
        colors:["rgb(253,8,100)","rgb(138,190,43)","rgb(13,195,202)","rgb(34,47,60)"],
        rotate: false
    });
})();

