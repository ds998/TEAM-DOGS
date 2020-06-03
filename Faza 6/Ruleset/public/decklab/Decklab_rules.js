function addRule(adderRow, rule = null) {
    if (selected == null) {
        alert("Please select a card to add rules to or change the global rules section.");
        return;
    }

    if (!rule) {
        let count = selected.children[0].children[0];
        count.textContent = (parseInt(count.textContent, 10) + 1).toString(10);
    }

    addRule.rules = ['Draw', 'Draw until', 'Skip', '', '', 'Jump-in']; //Change Global Rule, View Card
    if (!isValid()) return;

    var newRow = ruleTableRef.insertRow($(adderRow).index() + 1);
    $(newRow).addClass("rulesRow");

    var newCell = newRow.insertCell(0);
    $(newCell).addClass("align-middle fit ruleSelectCell");

    let remBTN = document.createElement('button');
    remBTN.type = "button";
    remBTN.textContent = "X"
    $(remBTN).addClass("btn btn-danger removeitem");
    remBTN.addEventListener("click", function () {
        deleteRule(this.parentNode.parentNode);
    })
    newCell.appendChild(remBTN);

    var newSelect = document.createElement('SELECT');
    $(newSelect).addClass("custom-select ruleSelect");
    if (rule) addOptions(newSelect, addRule.rules, ['1', '2', '3', '4', '5', '6'], true, rule.type);
    else addOptions(newSelect, addRule.rules, ['1', '2', '3', '4', '5', '6'], true);
    newCell.appendChild(newSelect);
    if (rule) switch (rule.type) {
        case types.DRAW_RULE:
            addDrawRule(newRow, rule);
            break;
        case types.DRAW_UNTIL_RULE:
            addDrawUntilRule(newRow, rule);
            break;
        case types.SKIP_PLAYER_RULE:
            addSkipRule(newRow, rule);
            break;
        case types.CHANGE_RULE_RULE:
            //TO-DO
            break;
        case types.VIEW_CARD_RULE:
            addViewCardRule(newRow, rule);
            break;
        case types.JUMP_IN_RULE:
            addJumpInRule(newRow, rule);
            break;
    }
    else addDrawRule(newRow);

    $(newSelect).change(function () {
        changeRuleCell(this.parentNode.parentNode, this.options[this.selectedIndex].value);
    });


}

function deleteRule(row) {
    let ruleIndex = $(row).index();
    deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()].splice(ruleIndex, 1);
    sessionStorage.setItem("deck", JSON.stringify(deck));

    let count = selected.children[0].children[0];
    count.textContent = (parseInt(count.textContent, 10) - 1).toString(10);

    row.parentNode.deleteRow(ruleIndex);
}

function deckChangeRule(cardIndex, ruleIndex, rule) {
    if (!(cardIndex in deck))
        throw "Whoops, can't add rule to a card that's not in the deck."

    if (!(rule.suit in deck[cardIndex].rules)) deck[cardIndex].rules[rule.suit] = [];
    deck[cardIndex].rules[rule.suit][ruleIndex] = rule;

    sessionStorage.setItem("deck", JSON.stringify(deck));
}

function changeRuleCell(tr, rule) {
    tr.deleteCell(1);
    switch (rule) {
        case "1":
            addDrawRule(tr);
            break;
        case "2":
            addDrawUntilRule(tr);
            break;
        case "3":
            addSkipRule(tr);
            break;
        case "4":
            // Not Done yet
            break;
        case "5":
            addViewCardRule(tr);
            break;
        case "6":
            addJumpInRule(tr);
            break;
    }
}

// Rule Cell adders

function addDrawRule(tr, rule = null) {
    addDrawRule.triggers = ['On play', 'On draw'];
    addDrawRule.targets = ['Next Player', 'Previous Player', 'Player of choice', 'Random Player', 'Player who used this card'];
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Triggers
    let newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');
    if (rule) addOptions(newSelect, addDrawRule.triggers, ['1', '2'], true, rule.details.trigger);
    else addOptions(newSelect, addDrawRule.triggers, ['1', '2'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.trigger = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //Targets
    newCell.appendChild(document.createTextNode(" "));
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select targetSelect');
    if (rule) addOptions(newSelect, addDrawRule.targets, ['1', '2', '3', '4', '5'], true, rule.details.target);
    else addOptions(newSelect, addDrawRule.targets, ['1', '2', '3', '4', '5'], true);

    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        let ruleIndex = $(ruleRow).index();
        let ruleSuit = $("input[name='ruleRadio']:checked").val();
        if (this.value == deck[selectedIndex].rules[ruleSuit][ruleIndex].details.source) {
            if (this.value == "1") this.value = '2';
            else this.value = (parseInt(this.value, 10) - 1).toString(10);
        }
        deck[selectedIndex].rules[ruleSuit][ruleIndex].details.target = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //End text
    newCell.appendChild(document.createTextNode("\u00A0 draws "));
    let newInput = document.createElement('input');
    newInput.type = 'number';
    newInput.min = "1";
    newInput.max = MAX_DRAW_CARDS.toString(10);
    if (rule) newInput.value = rule.details.num_cards;
    else newInput.value = "1";
    newInput.required = "true";
    $(newInput).addClass("inputNumber");
    newCell.appendChild(newInput);
    newInput.addEventListener('focusout', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.num_cards = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });
    newCell.appendChild(document.createTextNode(" card/s from the "));

    //Sources
    newCell.appendChild(document.createTextNode(" "));
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select targetSelect');
    if (rule) addOptions(newSelect, addDrawRule.targets.concat(['Deck']), ['1', '2', '3', '4', '5', '6'], true, rule.details.source);
    else addOptions(newSelect, addDrawRule.targets.concat(['Deck']), ['1', '2', '3', '4', '5', '6'], true, '6');
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        let ruleIndex = $(ruleRow).index();
        let ruleSuit = $("input[name='ruleRadio']:checked").val();
        if (this.value == deck[selectedIndex].rules[ruleSuit][ruleIndex].details.target) this.value = '6';
        deck[selectedIndex].rules[ruleSuit][ruleIndex].details.source = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });
    newCell.appendChild(document.createTextNode(" ."));

    if (!rule) {
        rule = {
            suit: $("input[name='ruleRadio']:checked").val(),
            type: types.DRAW_RULE,
            details: {
                trigger: triggers.ON_PLAY,
                target: targets.NEXT,
                source: targets.DECK,
                num_cards: 1
            }
        }

        deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    }
}

function addDrawUntilRule(tr, rule = null) {
    addDrawUntilRule.triggers = ['On play', 'On draw'];
    addDrawUntilRule.targets = ['Next Player', 'Previous Player', 'Player of choice', 'Random Player', 'Player who used this card'];
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Triggers
    let newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');
    if (rule) addOptions(newSelect, addDrawUntilRule.triggers, ['1', '2'], true, rule.details.trigger);
    else addOptions(newSelect, addDrawUntilRule.triggers, ['1', '2'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.trigger = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //Targets
    newCell.appendChild(document.createTextNode(" "));
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select targetSelect');
    if (rule) addOptions(newSelect, addDrawUntilRule.targets, ['1', '2', '3', '4', '5'], true, rule.details.target);
    else addOptions(newSelect, addDrawUntilRule.targets, ['1', '2', '3', '4', '5'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.target = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });


    //End text
    newCell.appendChild(document.createTextNode("\u00A0 draws until they draw any "));
    var opt;

    //Card
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');
    //Any Card
    opt = document.createElement('option');
    opt.appendChild(document.createTextNode("card"));
    opt.value = 'ANY';
    if (rule) {
        if (rule.details.card == "ANY") opt.selected = true;
    } else opt.selected = true;
    newSelect.appendChild(opt);
    //Any Different Card
    opt = document.createElement('option');
    opt.appendChild(document.createTextNode("different card"));
    if (rule) {
        if (rule.details.card == "DIFFERENT") opt.selected = true;
    } else opt.value = 'DIFFERENT';
    newSelect.appendChild(opt);

    for (let i = 0; i < deck.length; i++) {
        opt = document.createElement('option');
        opt.appendChild(document.createTextNode(deck[i].name));
        opt.value = i.toString(10);
        if (rule && rule.details.card == opt.value) opt.selected = true;
        newSelect.appendChild(opt);
    }
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.card = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //Middle text
    newCell.appendChild(document.createTextNode("\u00A0 of \u00A0"));

    //Suit
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select suitSelect');
    if (rule) addOptions(newSelect, ["any suit", "the same suit", "a different suit"].concat(suits), ['ANY', 'SAME', 'DIFFERENT', '0', '1', '2', '3'], true, rule.details.suit);
    else addOptions(newSelect, ["any suit", "the same suit", "a different suit"].concat(suits), ['ANY', 'SAME', 'DIFFERENT', '0', '1', '2', '3'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.suit = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //End text
    newCell.appendChild(document.createTextNode(" ."));

    if (!rule) {
        rule = {
            suit: $("input[name='ruleRadio']:checked").val(),
            type: types.DRAW_UNTIL_RULE,
            details: {
                trigger: triggers.ON_PLAY,
                target: targets.NEXT,
                card: 'ANY',
                suit: 'ANY'
            }
        }

        deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    }
}

function addSkipRule(tr, rule = null) {
    addSkipRule.triggers = ['On play', 'On draw'];
    addSkipRule.targets = ['Next Player', 'Previous Player', 'Player of choice', 'Random Player'];
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Triggers
    let newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');
    if (rule) addOptions(newSelect, addSkipRule.triggers, ['1', '2'], true, rule.details.trigger);
    else addOptions(newSelect, addSkipRule.triggers, ['1', '2'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.trigger = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //Targets
    newCell.appendChild(document.createTextNode(" "));
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select targetSelect');
    if (rule) addOptions(newSelect, addSkipRule.targets, ['1', '2', '3', '4'], true, rule.details.target);
    else addOptions(newSelect, addSkipRule.targets, ['1', '2', '3', '4'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.target = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //End text
    newCell.appendChild(document.createTextNode("\u00A0 losses a turn."));

    if (!rule) {
        rule = {
            suit: $("input[name='ruleRadio']:checked").val(),
            type: types.SKIP_PLAYER_RULE,
            details: {
                trigger: triggers.ON_PLAY,
                target: targets.NEXT,
            }
        }

        deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    }
}

function addViewCardRule(tr, rule = null) {
    addViewCardRule.triggers = ['On play', 'On draw'];
    addViewCardRule.sources = ['Next Player', 'Previous Player', 'Player of choice', 'Random Player', 'Deck'];
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Triggers
    let newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');
    if (rule) addOptions(newSelect, addViewCardRule.triggers, ['1', '2'], true, rule.details.trigger);
    else addOptions(newSelect, addViewCardRule.triggers, ['1', '2'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.trigger = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //Middle text
    newCell.appendChild(document.createTextNode("\u00A0the player who used this card gets to view "));
    let newInput = document.createElement('input');
    newInput.type = 'number';
    newInput.min = "1";
    newInput.max = MAX_VIEW_CARDS.toString(10);
    if (rule) newInput.value = rule.details.num_cards;
    else newInput.value = "1";
    newInput.required = "true";
    $(newInput).addClass("inputNumber");
    newCell.appendChild(newInput);
    newInput.addEventListener('focusout', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.num_cards = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });
    newCell.appendChild(document.createTextNode(" card/s from the\u00A0"));

    //Sources
    newCell.appendChild(document.createTextNode(" "));
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select targetSelect');
    if (rule) addOptions(newSelect, addViewCardRule.sources, ['1', '2', '3', '4', '6'], true, rule.details.source);
    else addOptions(newSelect, addViewCardRule.sources, ['1', '2', '3', '4', '6'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.source = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //End text
    newCell.appendChild(document.createTextNode(" ."));

    if (!rule) {
        rule = {
            suit: $("input[name='ruleRadio']:checked").val(),
            type: types.VIEW_CARD_RULE,
            details: {
                trigger: triggers.ON_PLAY,
                source: targets.NEXT,
                num_cards: 1
            }
        }

        deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    }
}

function addJumpInRule(tr, rule = null) {
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Start text
    newCell.appendChild(document.createTextNode("\u00A0This card can jump-in on any \u00A0"));

    //Card
    let newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select triggerSelect');

    var opt;
    //Any Card
    opt = document.createElement('option');
    opt.appendChild(document.createTextNode("card"));
    if (rule) {
        if (rule.details.card == "ANY") opt.selected = true;
    } else opt.value = 'ANY';
    opt.selected = true;
    newSelect.appendChild(opt);
    //Any Different Card
    opt = document.createElement('option');
    opt.appendChild(document.createTextNode("different card"));
    if (rule) {
        if (rule.details.card == "DIFFERENT") opt.selected = true;
    } else opt.value = 'DIFFERENT';
    newSelect.appendChild(opt);

    for (let i = 0; i < deck.length; i++) {
        opt = document.createElement('option');
        opt.appendChild(document.createTextNode(deck[i].name));
        opt.value = i.toString(10);
        if (rule && rule.details.card == opt.value) opt.selected = true;
        newSelect.appendChild(opt);
    }
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.card = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //Middle text
    newCell.appendChild(document.createTextNode("\u00A0 of \u00A0"));

    //Suit
    newSelect = document.createElement('select');
    $(newSelect).addClass('custom-select suitSelect');
    if (rule) addOptions(newSelect, ["any suit", "the same suit", "a different suit"].concat(suits), ['ANY', 'SAME', 'DIFFERENT', '0', '1', '2', '3'], true, rule.details.suit);
    else addOptions(newSelect, ["any suit", "the same suit", "a different suit"].concat(suits), ['ANY', 'SAME', 'DIFFERENT', '0', '1', '2', '3'], true);
    newCell.appendChild(newSelect);
    newSelect.addEventListener('change', function () {
        let ruleRow = this.parentNode.parentNode;
        deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()][$(ruleRow).index()].details.suit = this.value;
        sessionStorage.setItem("deck", JSON.stringify(deck));
    });

    //End text
    newCell.appendChild(document.createTextNode(" ."));

    if (!rule) {
        rule = {
            suit: $("input[name='ruleRadio']:checked").val(),
            type: types.JUMP_IN_RULE,
            details: {
                card: 'ANY',
                suit: 'ANY'
            }
        }

        deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    }
}

function createTableFromRules(rules) {
    $(".rulesRow:not(.topRow):not(.addRuleRow)").remove();
    let adderRow = $(".addRuleRow");
    let suit = $("input[name='ruleRadio']:checked").val();
    let i = 0;
    if (suit in rules) rules[suit].forEach(rule => {
        addRule(adderRow, rule);
    });
}

// Global Rules

function addGlobalRules(globalRules = null) {
    if (selected == null) {
        alert("Please select a card to add rules to or change the global rules section.");
        return;
    }

    addGlobalRules.rules = ['Win Condition', 'Number of players', 'Number of decks', 'Play order', 'Starting cards', 'Hand Limit', 'Cards drawn per turn', 'Cards played per turn', "When can't play", 'Same card Jump-in'];
    if (!isValid()) return;

    let adderRow = $(".addRuleRow");
    $(".rulesRow:not(.topRow):not(.addRuleRow)").remove();

    for (let i = 0; i < addGlobalRules.rules.length; i++) {
        var newRow = ruleTableRef.insertRow($(adderRow).index() + 1);
        $(newRow).addClass("rulesRow");

        var newCell = document.createElement('th');
        newRow.appendChild(newCell);
        $(newCell).addClass("align-middle fit globalRuleNameCell ruleSelectCell");
        newCell.appendChild(document.createTextNode("\u00A0" + addGlobalRules.rules[i] + "\u00A0"));

        newCell = newRow.insertCell(1);
        let newElem;
        switch (i) {
            case 0:
                newElem = document.createElement('select');
                $(newElem).addClass('custom-select winConSelect');
                if (globalRules) addOptions(newElem, ["First to empty their hand wins", "First to 20 number of cards wins"], ['1', '2'], true, globalRules.winCon); //, "Last person with cards in their hand wins"
                else addOptions(newElem, ["First to empty their hand wins.", "First to 20 number of cards wins"], ['1', '2'], true);
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.winCon = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });
                break;
            case 1:
                newCell.appendChild(document.createTextNode("\u00A0The minimum number of players is: "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = MIN_NUM_PLAYERS.toString(10);
                newElem.max = MAX_NUM_PLAYERS.toString(10);
                if (globalRules) newElem.value = globalRules.minPlayers;
                else newElem.value = MIN_NUM_PLAYERS.toString(10);
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    if (this.value > globalRules.maxPlayers) this.value = globalRules.maxPlayers;
                    globalRules.minPlayers = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" . The maximum number of players is: "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = MIN_NUM_PLAYERS.toString(10);
                newElem.max = MAX_NUM_PLAYERS.toString(10);
                if (globalRules) newElem.value = globalRules.maxPlayers;
                else newElem.value = MAX_NUM_PLAYERS.toString(10);
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    if (this.value < globalRules.minPlayers) this.value = globalRules.minPlayers;
                    globalRules.maxPlayers = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" ."));

                break;
            case 2:
                newCell.appendChild(document.createTextNode("\u00A0There are "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = "1";
                newElem.max = MAX_STARTING_DECK.toString(10);
                if (globalRules) newElem.value = globalRules.deckNum;
                else newElem.value = "2";
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.deckNum = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" copies of the deck in play."));
                break;
            case 3:
                newElem = document.createElement('select');
                $(newElem).addClass('custom-select orderSelect');
                if (globalRules) addOptions(newElem, ["Play proceeds clockwise", "Play proceeds counterclockwise"], ['1', '-1'], true, globalRules.order);
                else addOptions(newElem, ["Play proceeds clockwise", "Play proceeds counterclockwise"], ['1', '-1'], true);
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.order = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });
                break;
            case 4:
                newCell.appendChild(document.createTextNode("\u00A0Each player starts with "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = "1";
                newElem.max = MAX_STARTING_HAND.toString(10);
                if (globalRules) newElem.value = globalRules.startingCards;
                else newElem.value = "7";
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.startingCards = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" card/s."));
                break;
            case 5:
                newCell.appendChild(document.createTextNode("\u00A0Each player can have a max of "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = "1";
                newElem.max = MAX_HAND_LIMIT.toString(10);
                if (globalRules) newElem.value = globalRules.handLimit;
                else newElem.value = MAX_HAND_LIMIT.toString(10);
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.handLimit = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" card/s in their hand."));
                break;
            case 6:
                newCell.appendChild(document.createTextNode("\u00A0Each player draws "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = "0";
                newElem.max = MAX_DRAW_CARDS.toString(10);
                if (globalRules) newElem.value = globalRules.cardsDrawnPerTurn;
                else newElem.value = "0";
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.cardsDrawnPerTurn = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" card/s at the beginning of their turn."));
                break;
            case 7:
                newCell.appendChild(document.createTextNode("\u00A0Each player can play "));

                newElem = document.createElement('input');
                newElem.type = 'number';
                newElem.min = "1";
                newElem.max = MAX_CARDS_PLAYED.toString(10);
                if (globalRules) newElem.value = globalRules.cardsPerTurn;
                else newElem.value = "1";
                newElem.required = "true";
                $(newElem).addClass("inputNumber");
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.cardsPerTurn = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });

                newCell.appendChild(document.createTextNode(" card/s per turn."));
                break;
            case 8:
                newCell.appendChild(document.createTextNode("\u00A0When a player can't play any of their cards "));
                newElem = document.createElement('select');
                $(newElem).addClass('custom-select winConSelect');
                if (globalRules) addOptions(newElem, ["they draw 1 card and play it or pass", "they draw until they can play a card", "they pass"], ['1', '2', '3'], true, globalRules.cantPlay);
                else addOptions(newElem, ["they draw 1 card and play it or pass", "they draw until they can play a card", "they pass"], ['1', '2', '3'], true);
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.cantPlay = this.value;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });
                break;
            case 9:
                newCell.appendChild(document.createTextNode("\u00A0Enabled: "));

                newElem = document.createElement('input');
                newElem.type = 'checkbox';
                if (globalRules) newElem.checked = globalRules.sameCardJumpIn;
                else newElem.checked = true;
                newElem.required = "true";
                newCell.appendChild(newElem);
                newElem.addEventListener('focusout', function () {
                    globalRules.sameCardJumpIn = this.checked;
                    sessionStorage.setItem("globalRules", JSON.stringify(globalRules));
                });
                break;
        }
    }


    // $(newSelect).change(function () {
    //     changeRuleCell(this.parentNode.parentNode, this.options[this.selectedIndex].value);
    // });


}


function addspecialGlobalRule(adderRow, specialRule = null) {
    if (selected == null) {
        alert("Please select a card to add rules to or change the global rules section.");
        return;
    }

    addspecialGlobalRule.rules = ['Gamble', 'Trash Hand', 'Inflation', 'Bonus'];
    if (!isValid()) return;

    var newRow = ruleTableRef.insertRow($(adderRow).index() + 1);
    $(newRow).addClass("rulesRow");

    var newCell = newRow.insertCell(0);
    $(newCell).addClass("align-middle fit ruleSelectCell");

    let remBTN = document.createElement('button');
    remBTN.type = "button";
    remBTN.textContent = "X"
    $(remBTN).addClass("btn btn-danger removeitem");
    remBTN.addEventListener("click", function () {
        deleteSpecialRule(this.parentNode.parentNode);
    })
    newCell.appendChild(remBTN);

    var newSelect = document.createElement('SELECT');
    $(newSelect).addClass("custom-select globalRuleSelect");
    if (specialRule) addOptions(newSelect, addspecialGlobalRule.rules, ['1', '2', '3', '4'], true, specialRule.type);
    else addOptions(newSelect, addspecialGlobalRule.rules, ['1', '2', '3', '4'], true);
    newCell.appendChild(newSelect);
    if (specialRule) switch (specialRule.type) {
        case specialTypes.GAMBLE_SR:
            addGambleSRule(newRow);
            break;
        case specialTypes.TRASH_HAND_SR:
            addTrashSRule(newRow);
            break;
        case specialTypes.INFLATION_SR:
            addInflationSRule(newRow);
            break;
        case specialTypes.BONUS_SR:
            //TO-DO
            break;
    }
    else addGambleSRule(newRow);

    $(newSelect).change(function () {
        changeSpecialRuleCell(this.parentNode.parentNode, this.options[this.selectedIndex].value);
    });
}

function deleteSpecialRule(row) {
    let ruleIndex = $(row).index();
    //deck[selectedIndex].rules[$("input[name='ruleRadio']:checked").val()].splice(ruleIndex,1);
    //sessionStorage.setItem("deck", JSON.stringify(deck));

    row.parentNode.deleteRow(ruleIndex);
}

//Global Rule Adders

function addGambleSRule(tr, rule = null) {
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Start text
    newCell.appendChild(document.createTextNode("\u00A0During their turn a player can flip the top card of the Deck and put it on the top of the discard pile."));

    // if (!rule) {
    //     rule = {
    //         suit: $("input[name='ruleRadio']:checked").val(),
    //         type: types.JUMP_IN_RULE,
    //         details: {
    //             card: 'ANY',
    //             suit: 'ANY'
    //         }
    //     }

    //     deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    // }
}

function addTrashSRule(tr, rule = null) {
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Start text
    newCell.appendChild(document.createTextNode("\u00A0During their turn a player can discard their hand and draw a new one."));

    // if (!rule) {
    //     rule = {
    //         suit: $("input[name='ruleRadio']:checked").val(),
    //         type: types.JUMP_IN_RULE,
    //         details: {
    //             card: 'ANY',
    //             suit: 'ANY'
    //         }
    //     }

    //     deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    // }
}

function addInflationSRule(tr, rule = null) {
    let newCell = tr.insertCell(1);
    $(newCell).addClass('align-middle');

    //Start text
    newCell.appendChild(document.createTextNode("\u00A0All numbers on card rules are increased by 1."));

    // if (!rule) {
    //     rule = {
    //         suit: $("input[name='ruleRadio']:checked").val(),
    //         type: types.JUMP_IN_RULE,
    //         details: {
    //             card: 'ANY',
    //             suit: 'ANY'
    //         }
    //     }

    //     deckChangeRule($(".deckTableRow").index(selected), $(".rulesRow").index(tr) - 1, rule);
    // }
}

function changeSpecialRuleCell(tr, rule) {
    tr.deleteCell(1);
    switch (rule) {
        case "1":
            addGambleSRule(tr);
            break;
        case "2":
            addTrashSRule(tr);
            break;
        case "3":
            addInflationSRule(tr);
            break;
        case "4":
            // Not Done yet
            break;
    }
}