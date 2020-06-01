class Ruleset {

    constructor(rules, controller) {
        this.rules = rules;
        this.myController = controller;

        this.onPlayMap = {};
        this.onDrawMap = {};
        this.onDiscardMap = {};
    }

    addEventHandlers() {
        if (this.rules) this.rules.forEach(rule => {
            let key = rule.cardMatcher.toString();
            switch (rule.trigger) {
                case triggers.ON_PLAY:
                    if (!(key in this.onPlayMap)) this.onPlayMap[key] = [];
                    this.onPlayMap[key].push(this.generateEventHandler(rule));
                    break;
                case triggers.ON_DRAW:
                    if (!(key in this.onDrawMap)) this.onDrawMap[key] = [];
                    this.onDrawMap[key].push(this.generateEventHandler(rule));
                    break;
                case triggers.ON_DISCARD:
                    if (!(key in this.onDiscardMap)) this.onDiscardMap[key] = [];
                    this.onDiscardMap[key].push(this.generateEventHandler(rule));
                    break;
            }
        });
    }

    handleOnPlayEvent(event) {
        // Handle events matching exact card
        if (event.card.events[0][0]) this.onPlayMap[event.card.name].forEach(handler => {
            handler(event);
        });
        // Handle events matching value
        if (event.card.events[0][1]) this.onPlayMap[event.card.value].forEach(handler => {
            handler(event);
        });
        // Handle events matching suit
        if (event.card.events[0][2]) this.onPlayMap[event.card.suit].forEach(handler => {
            handler(event);
        });
    }

    handleOnDrawEvent(event) {
        // Handle events matching exact card
        if (event.card.events[1][0]) this.onDrawMap[event.card.name].forEach(handler => {
            handler(event);
        });
        // Handle events matching value
        if (event.card.events[1][1]) this.onDrawMap[event.card.value].forEach(handler => {
            handler(event);
        });
        // Handle events matching suit
        if (event.card.events[1][2]) this.onDrawMap[event.card.suit].forEach(handler => {
            handler(event);
        });
    }

    handleOnDrawEvent(event) {
        // Handle events matching exact card
        if (event.card.events[2][0]) this.onDrawMap[event.card.name].forEach(handler => {
            handler(event);
        });
        // Handle events matching value
        if (event.card.events[2][1]) this.onDrawMap[event.card.value].forEach(handler => {
            handler(event);
        });
        // Handle events matching suit
        if (event.card.events[2][2]) this.onDrawMap[event.card.suit].forEach(handler => {
            handler(event);
        });
    }

    generateEventHandler(rule) {
        switch (rule.type) {
            case types.DRAW_RULE:
                return (event) => {
                    Ruleset.drawFromRule(this.myController, rule, event);
                };
            case types.DRAW_UNTIL_RULE:
                return (event) => {
                    Ruleset.drawUntilRule(this.myController, rule, event);
                };
            case types.SKIP_PLAYER_RULE:
                return (event) => {
                    Ruleset.skip(this.myController, rule, event);
                };
            case types.CHANGE_RULE_RULE:
                return (event) => {
                    Ruleset.changeRule(this.myController, rule, event);
                };
            case types.VIEW_CARD_RULE:
                return (event) => {
                    Ruleset.viewCard(this.myController, rule, event);
                };
            case types.JUMP_IN_RULE:
                return (event) => {
                    Ruleset.jumpIn(this.myController, rule, event);
                };
        }
    }

    retEventMap(card) {
        let ret = [
            [false, false, false],
            [false, false, false],
            [false, false, false]
        ];

        // One itteration for each type of card trigger (except triggers.PASSIVE)

        if (card.name in this.onPlayMap)  ret[0][0] = true;
        if (card.value in this.onPlayMap) ret[0][1] = true;
        if (card.suit in this.onPlayMap)  ret[0][2] = true;

        if (card.name in this.onDrawMap)  ret[1][0] = true;
        if (card.value in this.onDrawMap) ret[1][1] = true;
        if (card.suit in this.onDrawMap)  ret[1][2] = true;

        if (card.name in this.onDiscardMap)  ret[2][0] = true;
        if (card.value in this.onDiscardMap) ret[2][1] = true;
        if (card.suit in this.onDiscardMap)  ret[2][2] = true;

        return ret;
    }

    static drawFromRule(controller, rule, event) {
        if (rule.type != types.DRAW_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](controller, rule, event);
        let source = TargetFunctions[rule.detail.source](controller, rule, event);

        draw(event.detail.player, player, num, source, card);
        //player.draw(rule.detail.num_cards, source);
    }

    static drawUntilRule(controller, rule, event) {
        if (rule.type != types.DRAW_UNTIL_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](controller, rule, event);
        let source = TargetFunctions[rule.detail.source](controller, rule, event);

        drawUntil(event.detail.player, player, source, card, rule.detail.target_card);
    }
}

function strToRules(str, cards, suits) {
    let ret = str.split(';');
    for (let r = 0; r < ret.length; r++) {
        let ruleCode = ret[r].split(',');
        let card = deck.values[ruleCode[0]];
        let suit;
        if (ruleCode[1] = 'a') suit = deck.suits[''];
        else suit = deck.suits[ruleCode[1]];
        let type = ruleCode[2];

        switch (type) {
            case types.DRAW_RULE:
                let trigger = ruleCode[3];
                let target = ruleCode[4];
                let target_can_be_cur = false;
                let source = ruleCode[5];
                let num_cards = ruleCode[6];
                let counteraction = ruleCode[7];
                ret[r] = new DrawRule(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction);
                break;
            case types.DRAW_UNTIL_RULE:
                let trigger = ruleCode[3];
                let target = ruleCode[4];
                let target_can_be_cur = false;
                let source = targets.DECK;
                let target_card;
                if (ruleCode[5]=='d') target_card='d';
                else target_card = ruleCode[5];
                let target_suit = ruleCode[6];
                if (ruleCode[6]=='d') target_card='d';
                else if (ruleCode[6]=='s') target_card='d';
                else target_card = ruleCode[5];
                let counteraction = ruleCode[7];
                ret[r] = new DrawUntilRule(card, suit, type, trigger, target, target_can_be_cur, source, target_card, target_suit, counteraction);
                break;
            case types.SKIP_PLAYER_RULE:
                let trigger = ruleCode[3];
                let target = ruleCode[4];
                let target_can_be_cur = false;
                let source = ruleCode[5];
                let num_cards = ruleCode[6];
                let counteraction = ruleCode[7];
                ret[r] = new DrawRule(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction);
                break;
            case types.CHANGE_RULE_RULE:
                let trigger = ruleCode[3];
                let target = ruleCode[4];
                let target_can_be_cur = false;
                let source = ruleCode[5];
                let num_cards = ruleCode[6];
                let counteraction = ruleCode[7];
                ret[r] = new DrawRule(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction);
                break;
            case types.VIEW_CARD_RULE:
                let trigger = ruleCode[3];
                let target = ruleCode[4];
                let target_can_be_cur = false;
                let source = ruleCode[5];
                let num_cards = ruleCode[6];
                let counteraction = ruleCode[7];
                ret[r] = new DrawRule(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction);
                break;
            case types.JUMP_IN_RULE:
                let trigger = ruleCode[3];
                let target = ruleCode[4];
                let target_can_be_cur = false;
                let source = ruleCode[5];
                let num_cards = ruleCode[6];
                let counteraction = ruleCode[7];
                ret[r] = new DrawRule(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction);
                break;
        }
    }
}