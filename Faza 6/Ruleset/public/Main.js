//Version: 1.0 Maja
var deck_image_1 = document.getElementById("deck_1");
var deck_image_2 = document.getElementById("deck_2");
var deck_image_3 = document.getElementById("deck_3");
var deck_image_4 = document.getElementById("deck_4");
var deck_image_5 = document.getElementById("deck_5");
var deck_image_6 = document.getElementById("deck_6");
var deck_image_7 = document.getElementById("deck_7");
var deck_image_8 = document.getElementById("deck_8");
var deck_image_9 = document.getElementById("deck_9");

function title_start_1()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 1";
}

function title_start_2()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 2";
}

function title_start_3()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 3";
}

function title_start_4()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 4";
}

function title_start_5()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 5";
}

function title_start_6()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 6";
}

function title_start_7()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 7";
}

function title_start_8()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 8";
}

function title_start_9()  {
    document.getElementById('table_title').innerHTML = "HIGHLIGHTED DECK 9";
}

function title_end() {
    document.getElementById('table_title').innerHTML = "";
}

deck_image_1.addEventListener('mouseenter', title_start_1);
deck_image_2.addEventListener('mouseenter', title_start_2);
deck_image_3.addEventListener('mouseenter', title_start_3);
deck_image_4.addEventListener('mouseenter', title_start_4);
deck_image_5.addEventListener('mouseenter', title_start_5);
deck_image_6.addEventListener('mouseenter', title_start_6);
deck_image_7.addEventListener('mouseenter', title_start_7);
deck_image_8.addEventListener('mouseenter', title_start_8);
deck_image_9.addEventListener('mouseenter', title_start_9);
deck_image_1.addEventListener('mouseleave', title_end);
deck_image_2.addEventListener('mouseleave', title_end);
deck_image_3.addEventListener('mouseleave', title_end);
deck_image_4.addEventListener('mouseleave', title_end);
deck_image_5.addEventListener('mouseleave', title_end);
deck_image_6.addEventListener('mouseleave', title_end);
deck_image_7.addEventListener('mouseleave', title_end);
deck_image_8.addEventListener('mouseleave', title_end);
deck_image_9.addEventListener('mouseleave', title_end);

function popup() {
    setTimeout(time_popup,1000);
}

function time_popup() {
    document.getElementById("main_popup").style.display = "block";
}

function closeForm() {
    document.getElementById("main_popup").style.display = "none";
}

window.addEventListener('load', popup);