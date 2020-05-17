function deckAdd(elem) {
    var deck = sessionStorage.getItem("deck");

    if(deck == null) deck=[];
    else deck = JSON.parse(x);

    deck.push(elem);

    sessionStorage.setItem("deck", JSON.stringify(deck));
}

function setup() {
    document.getElementsByClassName("deckTableRow");
}


if (document.readyState == 'loading') {
    document.addEventListener('load', setup());
} else {
    setup();
}
var selected = null;

$(document).ready(function () {
    $('tr').click(function () {
        //Check to see if background color is set or if it's set to white.
        if ($(this).text().includes("+ Add card")) {
            $(this).css('background', 'brown');
        } else {
            if (selected)  $(selected).css('background', '');
            selected = this;
            $(selected).cells[1].children[0].prop("disabled", false);
            $(selected).css('background', '#f69d52');
        }
    });
});