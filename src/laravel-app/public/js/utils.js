function dropdownFunction(element) {
    var dropdowns = document.getElementsByClassName('dropdown-content');
    var i;
    let list = element.parentElement.parentElement.getElementsByClassName('dropdown-content')[0];
    list.classList.add('target');
    for (i = 0; i < dropdowns.length; i++) {
        if (!dropdowns[i].classList.contains('target')) {
            dropdowns[i].classList.add('hidden');
        }
    }
    list.classList.toggle('hidden');
}

function dropdownHandler(element) {
    let single = element.getElementsByTagName('ul')[0];
    single.classList.toggle('hidden');
}

// function sidebarHandler() {
//     sideBar.classList.toggle('hidden');
// }

function dropdownHandler(element) {
    let single = element.getElementsByTagName('ul')[0];
    single.classList.toggle('hidden');
}
let sideBar = document.getElementById('mobile-nav');
let menu = document.getElementById('menu');
let cross = document.getElementById('cross');

const sidebarHandler = (check) => {
    if (check) {
        sideBar.style.transform = 'translateX(0px)';
        menu.classList.add('hidden');
    } else {
        sideBar.style.transform = 'translateX(-100%)';
        menu.classList.remove('hidden');
    }
};
function dropdownHandler(element) {
    let single = element.getElementsByTagName('ul')[0];
    single.classList.toggle('hidden');
}
