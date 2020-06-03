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
            deckNum:"2",
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
    let globalRulesStr = [];
    deck.forEach(card => {
        cards.push(card.name);
    });

    for (let c = 0; c < deck.length; c++) {
        Object.keys(deck[c].rules).forEach(ruleCategory => {
            deck[c].rules[ruleCategory].forEach(rule => {

                let ruleStr = [];
                ruleStr.push(c);
                //Suit
                if (rule.suit == 'all') ruleStr.push("a");
                else {
                    let index = suits.findIndex((el) => el == rule.suit);
                    ruleStr.push(index);
                }
                //Type
                ruleStr.push(rule.type);
                //Details
                switch (rule.type) {
                    case 1:
                        //Draw
                        ruleStr.push(rule.details.trigger);
                        ruleStr.push(rule.details.target);
                        ruleStr.push(rule.details.num_cards);
                        ruleStr.push(rule.details.source);
                        ruleStr.push(0); // Counteraction
                        break;
                    case 2:
                        //Draw Until
                        ruleStr.push(rule.details.trigger);
                        ruleStr.push(rule.details.target);
                        switch (rule.details.card) {
                            case "ANY":
                                ruleStr.push("a");
                                break;
                            case "DIFFERENT":
                                ruleStr.push("d");
                                break;
                            default:
                                ruleStr.push(rule.details.card);
                                break;
                        }
                        switch (rule.details.suit) {
                            case "ANY":
                                ruleStr.push("a");
                                break;
                            case "DIFFERENT":
                                ruleStr.push("d");
                                break;
                            case "SAME":
                                ruleStr.push("s");
                                break;
                            default:
                                ruleStr.push(rule.details.suit);
                                break;
                        }
                        ruleStr.push(0); // Counteraction
                        break;
                    case 3:
                        //Skip
                        ruleStr.push(rule.details.trigger);
                        ruleStr.push(rule.details.target);
                        break;
                    case 4:
                        // Not Done yet
                        break;
                    case 5:
                        //View Card
                        ruleStr.push(rule.details.trigger);
                        ruleStr.push(rule.details.source);
                        ruleStr.push(rule.details.num_cards);
                        break;
                    case 6:
                        //Jump In
                        switch (rule.details.card) {
                            case "ANY":
                                ruleStr.push("a");
                                break;
                            case "DIFFERENT":
                                ruleStr.push("d");
                                break;
                            default:
                                ruleStr.push(rule.details.card);
                                break;
                        }
                        switch (rule.details.suit) {
                            case "ANY":
                                ruleStr.push("a");
                                break;
                            case "DIFFERENT":
                                ruleStr.push("d");
                                break;
                            case "SAME":
                                ruleStr.push("s");
                                break;
                            default:
                                ruleStr.push(rule.details.suit);
                                break;
                        }
                        break;
                }
                rules.push(ruleStr.join());
            });
        });
    }

    globalRulesStr.push(globalRules.winCon);
    globalRulesStr.push(globalRules.minPlayers);
    globalRulesStr.push(globalRules.maxPlayers);
    globalRulesStr.push(globalRules.deckNum);
    globalRulesStr.push(globalRules.order);
    globalRulesStr.push(globalRules.startingCards);
    globalRulesStr.push(globalRules.handLimit);
    globalRulesStr.push(globalRules.cardsDrawnPerTurn);
    globalRulesStr.push(globalRules.cardsPerTurn);
    globalRulesStr.push(globalRules.cantPlay);
    if (globalRules.sameCardJumpIn) globalRulesStr.push(1);
    else globalRulesStr.push(0);
    //globalRulesStr.push(globalRules.specialRules);

    cards = cards.join();
    rules = rules.join(';');
    globalRulesStr = globalRulesStr.join(';');
    let suitSTR = [];
    if ($("#clubsCB").is(":checked")) suitSTR.push('1');
    else suitSTR.push('0');
    if ($("#diamondsCB").is(":checked")) suitSTR.push('1');
    else suitSTR.push('0');
    if ($("#spadesCB").is(":checked")) suitSTR.push('1');
    else suitSTR.push('0');
    if ($("#heartsCB").is(":checked")) suitSTR.push('1');
    else suitSTR.push('0');

    $("#deck").val(cards);
    $("#suits").val(suitSTR.join(''));
    $("#rules").val(rules);
    $("#globalRules").val(globalRulesStr);
    
    // DEBUG
    // console.log("Cards: " + cards);
    // console.log("Suits: " + suitSTR.join(''));
    // console.log("Rules: " + rules);
    // console.log("Global Rules: " + globalRulesStr);
}