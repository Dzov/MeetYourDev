// Open tabs on advert home page 

function openTab(tabName) {
    var i;
    var x = document.getElementsByClassName("tab");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block"; 
}

function activeTab(currentTab) {
    var j;
    var y = document.getElementsByClassName("home__tab");
    for (j = 0; j < y.length; j++) {
        y[j].style.color = "grey";
    }
    document.getElementById(currentTab).style.color = "white"; 
}