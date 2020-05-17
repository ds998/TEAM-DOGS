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
var selectedInput = null;

$(document).ready(function () {
    $('tr').click(function () {
        //Check to see if background color is set or if it's set to white.
        if ($(this).text().includes("+ Add card")) {
            $(this).css('background', 'brown');
        } else {
            if (this != selected && selected != null)  {
                $(selectedInput).prop("disabled", true);
                $(selected).css('background', '');
            }
            selected = this;
            selectedInput = selected.cells[1].children[0];
            $(selectedInput).prop("disabled", false);
            $(selected).css('background', '#875B50');
        }
    });
});