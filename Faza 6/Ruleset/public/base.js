$(document).ready(function () {
    let lightTheme = deck = sessionStorage.getItem("light-theme");
    if (lightTheme)
        setLightTheme();
});

function setDarkTheme() {
    let root = document.documentElement;
    root.style.setProperty('--main-bg-color', "#0B294B");
    root.style.setProperty('--main-brand-color', "#F69D52");
    root.style.setProperty('--border-brand-color', "#d18140");
    root.style.setProperty('--shadow-brand-color', "#f7bb61c7");
    root.style.setProperty('--hover-brand-color', "#f7B37D");
    root.style.setProperty('--navbar-color', "transparent");
    root.style.setProperty('--content-color', "#23222399");
    root.style.setProperty('--text-brand-color', "black");
    root.style.setProperty('--text-navbar-color', "white");
    root.style.setProperty('--text-content-color', "#F69D52");
    root.style.setProperty('--input-bg-navbar-color', "#ffffff66");
    root.style.setProperty('--input-bg-brand-color', "#ffffff66");
    root.style.setProperty('--main-success-color', "#7faa52");
    root.style.setProperty('--border-success-color', "#6e9445");
    root.style.setProperty('--hover-success-color', "#9cb682");
    root.style.setProperty('--shadow-success-color', "#93be65c7");

    $(".table-dark").each(function () {
        $(this).addClass("table-light");
        $(this).removeClass("table-dark");
    })
}

function setLightTheme() {
    let root = document.documentElement;
    root.style.setProperty('--main-bg-color', "#F3F6F5");
    root.style.setProperty('--main-brand-color', "#F69D52");
    root.style.setProperty('--border-brand-color', "#d18140");
    root.style.setProperty('--shadow-brand-color', "#f7bb61c7");
    root.style.setProperty('--hover-brand-color', "#f7B37D");
    root.style.setProperty('--navbar-color', "#232223");
    root.style.setProperty('--content-color', "#232223");
    root.style.setProperty('--text-brand-color', "black");
    root.style.setProperty('--text-content-color', "#F3F6F5");
    root.style.setProperty('--input-bg-navbar-color', "#ffffff66");
    root.style.setProperty('--input-bg-brand-color', "#ffffff66");
    root.style.setProperty('--main-success-color', "#7faa52");
    root.style.setProperty('--border-success-color', "#6e9445");
    root.style.setProperty('--hover-success-color', "#9cb682");
    root.style.setProperty('--shadow-success-color', "#93be65c7");

    $(".table-light").each(function () {
        $(this).addClass("table-dark");
        $(this).removeClass("table-light");
    })
}