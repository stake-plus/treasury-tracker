/* This script file refers to functions needed for the sidebar to work in the Referenda and Analytics page */

//Initializes sidebar on load to make sure the right options are checked off 
function sidebarInit(){
    let checkboxlist = document.querySelectorAll('.checkbox-list');
    let checkbox_hidden = document.querySelectorAll('.checkbox-list-hidden');

    for(var i = 0; i < checkbox_hidden.length; i++){
        if(checkbox_hidden[i].checked == false){
            checkboxlist[i].classList.remove('checked');
        }
    }

    let items = document.getElementById('sort-by').children;

    for(var i = 0; i < items.length; i++){
        items[i].addEventListener("click", function() {
            let items = document.getElementById('sort-by').children;
            const temp = items[0].innerHTML;
            items[0].innerHTML = this.innerHTML;
        });
    };
}

//Checks hidden form boxes
function checkBox(element, formBox){
    if(formBox.checked == true){
        element.classList.remove('checked');
        formBox.checked = false;
    }
    else{
        element.classList.add('checked');
        formBox.checked = true;
    }
}

//Selects all Networks
function selectAllNetworks(){
    const checkboxes = document.querySelectorAll(".checkbox-list.network");
    const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.network");

    for(var i = 0; i < checkboxes.length; i++){
        hiddenboxes[i].checked = true;
        checkboxes[i].classList.add('checked');
    }
}

//Deselects all networks
function deselectAllNetworks(){
    const checkboxes = document.querySelectorAll(".checkbox-list.network");
    const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.network");

    for(var i = 0; i < checkboxes.length; i++){
        hiddenboxes[i].checked = true;
        checkboxes[i].classList.remove('checked');
    }
}

//Deselects all categories
function deselectAll(){
    const checkboxes = document.querySelectorAll(".checkbox-list.category");
    const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.category");

    for(var i = 0; i < checkboxes.length; i++){
        hiddenboxes[i].checked = true;
        checkboxes[i].classList.remove('checked');
    }
}

//Selects all categories
function selectAll(){
    const checkboxes = document.querySelectorAll(".checkbox-list.category");
    const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.category");

    for(var i = 0; i < checkboxes.length; i++){
        hiddenboxes[i].checked = true;
        checkboxes[i].classList.add('checked');
    }
}

//Animation slide in for sidebar
function loadIn(){
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.add('right');
}