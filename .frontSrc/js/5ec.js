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