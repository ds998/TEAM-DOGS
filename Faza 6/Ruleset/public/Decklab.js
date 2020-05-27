function deckChangeName(index, name) {
    if (index in deck) {
        deck[index].name = name;
    } else {
        deck[index] = {
            'name': name,
            'rules': {}
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
                    deleteCard();
                } else if (!$(selectedInput).is(":focus")) {
                    // If names not empty, but name is not being edited
                    deleteCard();
                }
            }
            break;
        default:
            break;
    }
}

function deleteCard(index = selectedIndex) {
    if ($(".deckTableRow")[index].cells[0].children[0].textContent != "0") {
        if (!confirm("Are you sure you want to delte this card? Doing so will lose all the rules in it!")) return;
    }

    deck.splice(index, 1);
    sessionStorage.setItem("deck", JSON.stringify(deck));


    cardTableRef.deleteRow(index + 1);
    if (index == selectedIndex) {
        selected = null;
        selectedIndex = null;
        selectedInput = null;
        $('.rulesTitle').text("Rules");
        createTableFromRules([]);
    }
}

function selectRow(row) {
    if (!isValid()) return;
    if (row != selected) {
        if ($(".deckTableRow").index(row) == -1 && !$(row).hasClass("globalRulesRow")) return;
        if (selected != null) {
            $(selectedInput).prop("disabled", true);
            $(selectedInput).removeClass('selectedRow');
            $(selected).removeClass('selectedRow');
        }
        selected = row;
        selectedIndex = $(".deckTableRow").index(selected);

        $(selected).addClass('selectedRow');
        if (selectedIndex == -1) {
            //--- Global Rules selected ---
            selectedIndex = 'global';
            selectedInput = null;
            $('.rulesTitle').text("Global Rules");
        } else {
            selectedInput = selected.cells[1].children[0];
            $(selectedInput).prop("disabled", false);
            $(selectedInput).addClass('selectedRow');
            $('.rulesTitle').text(selectedInput.value + ": Rules");
        }

        if (selectedIndex == 'global') addGlobalRules(globalRules);
        else if (deck[selectedIndex]) createTableFromRules(deck[selectedIndex].rules);
        else createTableFromRules([]);
    }
}

function isValid(inputBox = selectedInput) {
    if (inputBox == null) return true;
    let str = inputBox.value;
    if (str.length > 0) {
        let can = true;
        let others = deck.slice(0, selectedIndex).concat(deck.slice(selectedIndex + 1));
        for (let c = 0; c < others.length; c++)
            if (others[c].name == str) {
                can = false;
                break;
            }
        return can;
    } else {
        return false;
    }
}

function preventLeave(e) {
    if (!isValid(this)) {
        e.preventDefault();
        $(this).focus();
    } else {
        deckChangeName($(".deckTableRow").index(this.parentNode.parentNode), $(this).val());
        $('.rulesTitle').text(selectedInput.value + ": Rules");
    }
}

function addCard(adderRow, card = null) {
    if (!isValid()) return;

    var newRow = cardTableRef.insertRow($(adderRow).index() + 1);
    $(newRow).addClass("deckTableRow cardRow");

    var newCell = document.createElement('th');
    newCell.scope = 'row';
    $(newCell).addClass("numRulesCell");
    var newText = document.createElement('span');
    if (card) {
        let count = 0;
        if ('all' in card.rules) count += card.rules['all'].length;
        suits.forEach(suit => {
            if (suit in card.rules) count += card.rules[suit].length;
        });
        newText.textContent = count.toString(10);
    } else newText.textContent = '0';
    newCell.appendChild(newText);

    let remBTN = document.createElement('button');
    remBTN.type = "button";
    remBTN.textContent = "X"
    remBTN.addEventListener("click", function () {
        deleteCard($(this.parentNode.parentNode).index());
    })
    $(remBTN).addClass("btn btn-danger removeitem");
    newCell.appendChild(remBTN);

    newRow.appendChild(newCell);

    newCell = newRow.insertCell(1);
    newText = document.createElement('INPUT');
    newText.type = 'text';
    newText.size = "15";
    newText.maxlength = "15";
    newText.required = "true";
    newText.disabled = "true";
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

$(document).ready(function () {
    cardTableRef = document.getElementById('cardTable');
    ruleTableRef = document.getElementById('ruleTable');
    document.addEventListener("keydown", KeyCheck);

    // - Loading from Memory --------------------------
    // --------- Deck ---------------------------------
    deck = sessionStorage.getItem("deck");
    if (deck == null || deck == "[]") {
        // let request = new XMLHttpRequest();
        // request.open('GET', 'defaultDeck.json');
        // request.onload = function() {
        //     deck = request.responseText;
        //     makeDeck();
        // }
        deck = "[{\"name\":\"Ace\",\"rules\":{}},{\"name\":\"2\",\"rules\":{}},{\"name\":\"3\",\"rules\":{}},{\"name\":\"4\",\"rules\":{}},{\"name\":\"5\",\"rules\":{}},{\"name\":\"6\",\"rules\":{}},{\"name\":\"7\",\"rules\":{}},{\"name\":\"8\",\"rules\":{}},{\"name\":\"9\",\"rules\":{}},{\"name\":\"10\",\"rules\":{}},{\"name\":\"Jack\",\"rules\":{}},{\"name\":\"Queen\",\"rules\":{}},{\"name\":\"King\",\"rules\":{}}]";
        makeDeck();
    } else makeDeck();

    // --------- Global Rules -------------------------
    globalRules = sessionStorage.getItem("globalRules");
    if (globalRules == null) {
        globalRules = {
            winCon: "1",
            minPlayers: MIN_NUM_PLAYERS.toString(10),
            maxPlayers: MAX_NUM_PLAYERS.toString(10),
            order: "1",
            startingCards: "7",
            handLimit: MAX_HAND_LIMIT.toString(10),
            cardsDrawnPerTurn: "0",
            cardsPerTurn: "1",
            cantPlay: "1",
            sameCardJumpIn: true,
            specialRules: {}
        }
    } else globalRules = JSON.parse(globalRules);

    // --------- Description --------------------------
    let descElem = $("#deckDecription");
    let description = sessionStorage.getItem("description");
    if (description != null) {
        descElem.val(description);
    }

    descElem.bind('focusout', function (e) {
        saveDescription(this);
    });

    // --------- Name ---------------------------------
    let nameElem = $("#deckName");
    let name = sessionStorage.getItem("name");
    if (name != null) {
        nameElem.val(name);
    }

    nameElem.bind('focusout', function (e) {
        saveName(this);
    });
    // ------------------------------------------------
    // - Adding Listeners -----------------------------

    let adderRow = $('.addCard')[0];

    adderRow.addEventListener('click', function () {
        addCard(this);
    });


    $('.addRuleRow').each(function () {
        this.addEventListener('click', function () {
            if (selectedIndex == 'global') {
                addspecialGlobalRule(this);
            } else {
                addRule(this);
            }
        });
    });

    $('.globalRulesRow').each(function () {
        this.addEventListener('click', function () {
            selectGlobal(this);
        });
    });

    $('.testingAtest').each(function () {
        addDrawUntilRule(this);
    });

    $('.ruleSelect').each(function () {
        this.addEventListener('change', function () {
            changeRuleCell(this.parentNode.parentNode, this.options[this.selectedIndex].value);
        });
    });

    $("input[name='ruleRadio']").change(function () {
        if (selected) createTableFromRules(deck[selectedIndex].rules);
    })

});

function selectGlobal(row) {
    selectRow(row);
}

function saveDescription(elem) {
    sessionStorage.setItem("description", elem.value);
}

function saveName(elem) {
    sessionStorage.setItem("name", elem.value);
}

function makeDeck() {
    deck = JSON.parse(deck);
    let adderRow = $('.addCard')[0];
    deck.forEach(card => {
        if (card) addCard(adderRow, card);
    });
}

function addOptions(select, names, values, isSelected = false, selectedValue = null) {
    // Adds options to select with the given names and values. If is selected is
    // true also selects the option with the value matching selectedValue or the first 
    // option if no selectedValue is given
    let opt;
    for (let i = 0; i < names.length; i++) {
        opt = document.createElement('option');
        opt.appendChild(document.createTextNode(names[i]));
        opt.value = values[i];
        if (isSelected) {
            if (selectedValue && selectedValue == values[i]) opt.selected = true;
            else if (i == 0) opt.selected = true;
        }
        select.appendChild(opt);
    }
}

function convertStoredInfo() {
    let cards = [];
    let rules = [];
    deck.forEach(card => {
        cards.add(card.name)
    });

    Object.keys(cards.rules).forEach(ruleCategory => {
        ruleCategory.forEach(rule => {
            let ruleStr = [];
            //Suit
            if (rule.suit == 'all') ruleStr.add("a");
            else {
                let index = suits.findIndex((el) => el == rule.suit);
                ruleStr.add(index);
            }
            //Type
            ruleStr.add(rule.type);
            //Details
            switch (rule.type) {
                case "1":
                    //Draw
                    ruleStr.add(rule.details.trigger);
                    ruleStr.add(rule.details.target);
                    ruleStr.add(rule.details.num_cards);
                    ruleStr.add(rule.details.source);
                    break;
                case "2":
                    //Draw Until
                    ruleStr.add(rule.details.trigger);
                    ruleStr.add(rule.details.target);
                    switch (rule.detail.card) {
                        case "ANY":
                            ruleStr.add("a");
                            break;
                        case "DIFFERENT":
                            ruleStr.add("d");
                            break;
                        default:
                            ruleStr.add(cards.findIndex((el) => el == rule.detail.card));
                            break;
                    }
                    switch (rule.detail.suit) {
                        case "ANY":
                            ruleStr.add("a");
                            break;
                        case "DIFFERENT":
                            ruleStr.add("d");
                            break;
                        case "SAME":
                            ruleStr.add("s");
                            break;
                        default:
                            ruleStr.add(suits.findIndex((el) => el == rule.detail.suit));
                            break;
                    }
                    break;
                case "3":
                    //Skip
                    ruleStr.add(rule.details.trigger);
                    ruleStr.add(rule.details.target);
                    break;
                case "4":
                    // Not Done yet
                    break;
                case "5":
                    //View Card
                    ruleStr.add(rule.details.trigger);
                    ruleStr.add(rule.details.source);
                    ruleStr.add(rule.details.num_cards);
                    break;
                case "6":
                    //Jump In
                    switch (rule.detail.card) {
                        case "ANY":
                            ruleStr.add("a");
                            break;
                        case "DIFFERENT":
                            ruleStr.add("d");
                            break;
                        default:
                            ruleStr.add(cards.findIndex((el) => el == rule.detail.card));
                            break;
                    }
                    switch (rule.detail.suit) {
                        case "ANY":
                            ruleStr.add("a");
                            break;
                        case "DIFFERENT":
                            ruleStr.add("d");
                            break;
                        case "SAME":
                            ruleStr.add("s");
                            break;
                        default:
                            ruleStr.add(suits.findIndex((el) => el == rule.detail.suit));
                            break;
                    }
                    break;
            }
            rules.add(ruleStr.join());
        });
    });

    cards = cards.join();
    rules = rules.join(';');
}