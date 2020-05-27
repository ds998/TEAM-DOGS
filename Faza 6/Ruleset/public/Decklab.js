function deckChangeName(index, name) {
    if (index in deck) {
        deck[index].name = name;
    } else {
        deck[index] = {
            'name': name,
            'rules': []
        };
    }

    sessionStorage.setItem("deck", JSON.stringify(deck));
}

function KeyCheck(event) {
    var KeyID = event.keyCode;
    switch (KeyID) {
        case 8:
            //backspace
        case 46:
            //delete
            if (selected != null) {
                if (selectedInput.value == "") {
                    // If names empty
                    if (selected.cells[0].textContent == 0) {
                        // And there are no rules
                        deleteCard();
                    } else {
                        // And there are some rules
                        if (confirm("Are you sure you want to delte this card? Doing so will lose all the rules in it!"))
                            deleteCard();
                    }
                } else if (!$(selectedInput).is(":focus")) {
                    // If names not empty, but name is not being edited
                    if (selected.cells[0].textContent == 0) {
                        // And there are no rules
                        deleteCard();
                    } else {
                        // And there are some rules
                        if (confirm("Are you sure you want to delte this card? Doing so will lose all the rules in it!"))
                            deleteCard();

                    }
                }
            }
            break;
        default:
            break;
    }
}

function deleteCard() {
    let selectedIndex = $("tr").index(selected);
    deck.splice(selectedIndex-1, 1);
    sessionStorage.setItem("deck", JSON.stringify(deck));


    cardTableRef.deleteRow(selectedIndex);
    selected = null;
    selectedInput = null;
    $('#ruleTable thead th').text("Rules");
}

function selectRow(row) {
    if (!isValid()) return;
    if (row != selected) {
        if (selected != null) {
            $(selectedInput).prop("disabled", true);
            $(selectedInput).removeClass('selectedRow');
            $(selected).removeClass('selectedRow');
        }
        selected = row;
        selectedInput = selected.cells[1].children[0];
        $(selectedInput).prop("disabled", false);
        $(selectedInput).addClass('selectedRow');
        $(selected).addClass('selectedRow');
        $('#ruleTable thead th').text(selectedInput.value + ": Rules");
    }
}

function isValid(inputBox = selectedInput) {
    if (inputBox == null) return true;
    let str = inputBox.value;
    if (str.length > 0) {
        return true;
    } else {
        return false;
    }
}

function preventLeave(e) {
    if (!isValid(this)) {
        e.preventDefault();
        $(this).focus();
    } else {
        deckChangeName($("tr").index(this.parentNode.parentNode)-1, $(this).val());
        $('#ruleTable thead th').text(selectedInput.value + ": Rules");
    }
}

function addCard(adderRow, card=null) {
    if (!isValid()) return;

    var newRow = cardTableRef.insertRow($("tr").index(adderRow));
    $(newRow).addClass("deckTableRow cardRow");

    var newText = document.createElement('th');
    newText.scope = 'row';
    newText.textContent = '0';
    newRow.appendChild(newText);

    var newCell = newRow.insertCell(1);
    newText = document.createElement('INPUT');
    newText.type = 'text';
    newText.size = "15";
    newText.maxlength = "15";
    newText.required = "true";
    $(newText).addClass("cardName");
    if (card) newText.value = card.name;
    newCell.appendChild(newText);

    if (!card) {
        selectRow(newRow);
        newText.focus();
    }
    $(newRow).click(function () {
        selectRow(this);
    });
    $(newText).bind('focusout', function (e) {
        preventLeave.bind(this)(e);
    });
}

function addRule(adderRow) {
    if (!isValid()) return;

    var newRow = ruleTableRef.insertRow($("tr").index(adderRow));
    $(newRow).addClass("deckTableRow ruleRow");

    var newText = document.createElement('th');
    newText.scope = 'row';
    newText.textContent = '0';
    newRow.appendChild(newText);

    var newCell = newRow.insertCell(1);
    newText = document.createElement('INPUT');
    newText.type = 'text';
    newText.size = "15";
    newText.maxlength = "15";
    newText.required = "true";
    $(newText).addClass("cardName");
    newCell.appendChild(newText);

    selectRow(newRow);
    newText.focus();
    $(newRow).click(function () {
        selectRow(this)
    });
    $(newText).bind('focusout', function (e) {
        preventLeave.bind(this)(e);
    });
}

var selected = null;
var selectedInput = null;
var cardTableRef;
var ruleTableRef;
var cardID = 0;
var deck;

$(document).ready(function () {
    cardTableRef = document.getElementById('cardTable');
    ruleTableRef = document.getElementById('ruleTable');
    document.addEventListener("keydown", KeyCheck);

    deck = sessionStorage.getItem("deck");
    if (deck == null) {
        // let request = new XMLHttpRequest();
        // request.open('GET', 'defaultDeck.json');
        // request.onload = function() {
        //     deck = request.responseText;
        //     makeDeck();
        // }
        deck = "[{\"name\":\"Ace\",\"rules\":[]},{\"name\":\"2\",\"rules\":[]},{\"name\":\"3\",\"rules\":[]},{\"name\":\"4\",\"rules\":[]},{\"name\":\"5\",\"rules\":[]},{\"name\":\"6\",\"rules\":[]},{\"name\":\"7\",\"rules\":[]},{\"name\":\"8\",\"rules\":[]},{\"name\":\"9\",\"rules\":[]},{\"name\":\"10\",\"rules\":[]},{\"name\":\"Jack\",\"rules\":[]},{\"name\":\"Queen\",\"rules\":[]},{\"name\":\"King\",\"rules\":[]}]";
        makeDeck();
    }
    else makeDeck();

    let adderRow = $('.addCard')[0];
    
    adderRow.addEventListener('click', function () {
        addCard(this);
    });
    

    $('.addRuleRow').each(function () {
        this.addEventListener('click', function () {
            addRule(this);
        });
    });

    $('.testingAtest').each(function () {
        addDrawRule(this);
    });
});
function makeDeck() {
    deck = JSON.parse(deck);
    let adderRow = $('.addCard')[0];
    deck.forEach(card => {
        if (card) addCard(adderRow, card);
    });
}
// Rule Cell adders

function addDrawRule(tr) {
    addDrawRule.triggers = ['On play', 'On draw', 'On discard'];
    addDrawRule.targets = ['Next Player', 'Previous Player', 'Player of choice', 'Random Player', 'Player who used this card'];
    let newCell = tr.insertCell(1);
    $(newCell).addClass('algn-mididle');

    let newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');
    var opt;
    for (let i=0;i<addDrawRule.triggers.length; i++) { 
        opt = document.createElement('option');
        opt.appendChild( document.createTextNode(addDrawRule.triggers[i]) );
        opt.value = i+1; 
        if (i==0) opt.selected = true;
        newSelect.appendChild(opt); 
    }
    newCell.appendChild(newSelect);

    newCell.appendChild(document.createTextNode(" "));
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select targetSelect');
    for (let i=0;i<addDrawRule.targets.length; i++) { 
        opt = document.createElement('option');
        opt.appendChild( document.createTextNode(addDrawRule.targets[i]) );
        opt.value = i+1; 
        if (i==0) opt.selected = true;
        newSelect.appendChild(opt); 
    }
    newCell.appendChild(newSelect);

    newCell.appendChild(document.createTextNode("\u00A0 draws "));
    let newInput = document.createElement('input');
    newInput.type = 'number';
    newInput.min = "1";
    newInput.max = "99";
    newInput.value = "1";
    newInput.required = "true";
    $(newInput).addClass("inputNumber");
    newCell.appendChild(newInput);
    newCell.appendChild(document.createTextNode("card/s."));
}