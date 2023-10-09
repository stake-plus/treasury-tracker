
const network_modal = document.getElementById('network-modal');
const toggle = document.querySelector('.slider.round');
const toggle_checked = document.querySelector('.toggle > input');

//Shows which wallets you are allowed to select
function selectWallet(){
    const wallet = document.querySelector('.wallets-container');
    wallet.style.display = "none";

    const wallet_form = document.getElementById('wallet-form');
    wallet_form.style.display = "block";
}

//Shows which networks you are allowed to select
function selectNetwork(){
    network_modal.style.display = "block";
}

//Closes the modal
function closeNetwork(){
    network_modal.style.display = "none";
}

//Checks whether to set mode to light or dark
document.addEventListener("DOMContentLoaded", function() {
    const serverMode = "<?= $this->request->getSession()->read('mode') ?>";

    let clientMode = sessionStorage.getItem('mode') || serverMode;
    const checkbox = document.getElementById('modeToggle');

    checkbox.checked = (clientMode === 'dark');
    applyColorMode(clientMode);
});

//Function that toggles the light or dark mode itself
function toggleMode() {
    const checkbox = document.getElementById('modeToggle');
    const mode = checkbox.checked ? 'light' : 'dark';
    sessionStorage.setItem('mode', mode);
    applyColorMode(mode);
}

function applyColorMode(mode) {
    if (mode === 'dark') {
        //Apply dark mode styling
        document.documentElement.style.setProperty('--dark', '#f4f4f4');
        document.documentElement.style.setProperty('--light', '#353535');
        document.documentElement.style.setProperty('--white', '#181818');
        document.documentElement.style.setProperty('--grey', '#6e6e6e');
        document.documentElement.style.setProperty('--dark-grey', '#e6e6e6');
        document.documentElement.style.setProperty('--light-grey', '#3d3d3d');
        document.documentElement.style.setProperty('--background', '#161616');
        document.documentElement.style.setProperty('--invert', 'invert(100%)');
    } 
    else {
        //Apply light mode styling
        document.documentElement.style.setProperty('--light', '#f4f4f4');
        document.documentElement.style.setProperty('--dark', '#353535');
        document.documentElement.style.setProperty('--white', '#ffffff');
        document.documentElement.style.setProperty('--grey', '#d9d9d9');
        document.documentElement.style.setProperty('--dark-grey', '#5F6368');
        document.documentElement.style.setProperty('--light-grey', '#e6e6e6');
        document.documentElement.style.setProperty('--background', '#E5EAEE');
        document.documentElement.style.setProperty('--invert', 'invert(0%)');
    }
}



const items = document.querySelectorAll('.navbar-secondary > .nav > .nav-link > .icon');
const parents = document.querySelectorAll('.navbar-secondary > .nav > .nav-link');

//Animation controller for header icon animations
function animInit(parent_container, container, lottie_path){
    const animator = {
        container: container,
        path: ('/img/icons/' + lottie_path),
        renderer: 'svg',
        loop: false,
        autoplay: false

    };

    const anim = lottie.loadAnimation(animator);

    //Plays animation once when hovering
    function playAnimation(){
        anim.play();
    }

    //Stops animation on when not hovering
    function stopAnimation(){
        anim.stop();
    }
    parent_container.addEventListener('mouseenter', playAnimation);
    parent_container.addEventListener('mouseleave', stopAnimation);
};

for(var i = 0; i < parents.length; i++){
    const animationPath = items[i].getAttribute('data-animation');
    animInit(parents[i], items[i], animationPath);
}


//Changes opacity of header tabs depending on which page user is on. Full opacity on current page, all other tabs are faded.
const site_url = window.location.href;
var check = 0;
const locations = ['referenda', 'chains', 'discussions', 'analytics', 'referendum'];

for(var i = 0; i < locations.length; i++){
    if(site_url.includes(locations[i])){
        if (locations[i] == 'referendum') {
            document.getElementById('referenda').style.opacity = 1;
        } 
        else {
            document.getElementById(locations[i]).style.opacity = 1;
        }
        check++;
    }
}

if(check <= 0){
    parents[0].style.opacity = '1';
}

