//Version: 1.0
var woofSound;
$(document).ready(function () {

    woof1Sound = new Audio("../../assets/navbar/bark1.mp3");
    woof1Sound.volume = 0.5;
    woof2Sound = new Audio("../../assets/navbar/bark2.mp3");
    woof2Sound.volume = 0.5;
    woof3Sound = new Audio("../../assets/navbar/bark3.mp3");
    woof3Sound.volume = 0.5;
    woof4Sound = new Audio("../../assets/navbar/bark4.mp3");
    woof4Sound.volume = 0.5;

    var dogsLogo = document.getElementById("byTeamDogs");
    dogsLogo.addEventListener('click', function() {
        switch (Math.floor(Math.random() * 3)) {
            case 0:
                woof1Sound.play().catch(error => {});
                break;
            case 1:
                woof2Sound.play().catch(error => {});
                break;
            case 2:
                woof3Sound.play().catch(error => {});
                break;
            case 3:
                woof4Sound.play().catch(error => {});
                break;
        }
    })

    
        var deck_images = document.getElementsByClassName("hd_image");

        deck_images[0].addEventListener('mouseenter', function(){title_start(deck_images[0].id)});
        deck_images[1].addEventListener('mouseenter', function(){title_start(deck_images[1].id)});
        deck_images[2].addEventListener('mouseenter', function(){title_start(deck_images[2].id)});
        deck_images[3].addEventListener('mouseenter', function(){title_start(deck_images[3].id)});
        deck_images[4].addEventListener('mouseenter', function(){title_start(deck_images[4].id)});
        deck_images[5].addEventListener('mouseenter', function(){title_start(deck_images[5].id)});
        deck_images[6].addEventListener('mouseenter', function(){title_start(deck_images[6].id)});
        deck_images[7].addEventListener('mouseenter', function(){title_start(deck_images[7].id)});
        deck_images[8].addEventListener('mouseenter', function(){title_start(deck_images[8].id)});
        deck_images[0].addEventListener('mouseleave', title_end);
        deck_images[1].addEventListener('mouseleave', title_end);
        deck_images[2].addEventListener('mouseleave', title_end);
        deck_images[3].addEventListener('mouseleave', title_end);
        deck_images[4].addEventListener('mouseleave', title_end);
        deck_images[5].addEventListener('mouseleave', title_end);
        deck_images[6].addEventListener('mouseleave', title_end);
        deck_images[7].addEventListener('mouseleave', title_end);
        deck_images[8].addEventListener('mouseleave', title_end);
    

    

    function title_start(str)  {
        document.getElementById('table_title').innerHTML = str;
    }

    function title_end() {
        document.getElementById('table_title').innerHTML = "";
    }

    function popup() {
        setTimeout(time_popup,1000);
    }

    function time_popup() {
        document.getElementById("main_popup").style.display = "block";
    }

    function closeForm() {
        document.getElementById("main_popup").style.display = "none";
    }

    //window.addEventListener('load', popup);
});