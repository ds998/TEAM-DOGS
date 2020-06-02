class Ruleset {

    constructor(rules, controller) {
        this.rules = rules;
        this.myController = controller;

        this.sameCardJumpIn=globalRules[10];

        this.onPlayMap = {};
        this.onDrawMap = {};
        this.onDiscardMap = {};
        this.passiveMap = {};
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
                case triggers.PASSIVE:
                    if (!(key in this.passiveMap)) this.passiveMap[key] = [];
                    this.passiveMap[key].push(this.generateEventHandler(rule));
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

    canJumpIn(card, cardDest) {
        if (!card || !cardDest) return false;
        if (this.sameCardJumpIn && card.suit == cardDest.suit && card.value == cardDest.value) return true;
        else if (card in this.passiveMap) {
            let cm=new CardMatcher(this.passiveMap[card].target_card,this.passiveMap[card].target_suit);
            return cm.isMatch(card);
        }
    }

    static drawFromRule(controller, rule, event) {
        if (rule.type != types.DRAW_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](controller, rule, event);
        let source = TargetFunctions[rule.detail.source](controller, rule, event);

        draw(event.detail.player.id, player.id, num, source, card);
        //player.draw(rule.detail.num_cards, source);
    }

    static drawUntilRule(controller, rule, event) {
        if (rule.type != types.DRAW_UNTIL_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](controller, rule, event);
        let source = TargetFunctions[rule.detail.source](controller, rule, event);
        let matcher = rule.detail.matcher;

        drawUntil(event.detail.player.id, player.id, source, card, rule.detail.target_card);
    }

    static skipRule(controller, rule, event) {
        if (rule.type != types.SKIP_PLAYER_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](controller, rule, event);

        skip(event.detail.player.id, player.id);
    }
    
    static changeRuleRule(controller, rule, event) {
        if (rule.type != types.CHANGE_RULE_RULE) throw 'Error: Rule and handler missmatch!';
        let source = TargetFunctions[rule.detail.source](controller, rule, event);

        viewCard(event.detail.player.id, source);
    }

    static viewCardRule(controller, rule, event) {
        if (rule.type != types.VIEW_CARD_RULE) throw 'Error: Rule and handler missmatch!';
        let source = TargetFunctions[rule.detail.source](controller, rule, event);

        viewCard(event.detail.player.id, source);
    }

    parseCard(card) {
        let cardTemplate = (value, suit) => {
            let name = value + ' of ' + suit;
            //returns key and values into each instance of the this.cards array
            return {
                'name': name,
                'suit': suit,
                'value': value,
                'events': null
            }
        }
        let mysuit;
        switch(card[1]) {
            case 'c':
                mysuit='Clubs';
                break;
            case 'd':
                mysuit='Diamonds';
                break;
            case 's':
                mysuit='Spades';
                break;
            case 'h':
                mysuit='Hearts';
                break;
        }
        var newCard = cardTemplate(cardNames[card.charCodeAt(0)-'@'.charCodeAt(0)], mysuit);
        newCard.events = this.retEventMap(newCard);
        return newCard;
    }
}

const suits=['Clubs', 'Diamonds', 'Spades', 'Hearts'];
suits['a']='';

function unparseCard(card) {
    let c;
    for (c=0; c<cardNames.length; c++) {
        if (cardNames[c] == card.value) break;
    }
    return String.fromCharCode(c+'@'.charCodeAt(0)) +card.suit[0].toLowerCase();
}

function strToRules(str, cards) {
    let rulesStr = str.split(';');
    let ret=[];
    for (let r = 0; r < rulesStr.length; r++) {
        let ruleCode = rulesStr[r].split(',');
        let card = cardNames[ruleCode[0]];
        let suit = suits[ruleCode[1]];
        let type = parseInt(ruleCode[2],10);
        let trigger, target, target_can_be_cur, source, num_cards, counteraction, target_card, target_suit;
        switch (type) {
            case types.DRAW_RULE:
                trigger = parseInt(ruleCode[3], 10);
                target = parseInt(ruleCode[4], 10);
                target_can_be_cur = false;
                num_cards = parseInt(ruleCode[5], 10);
                source = parseInt(ruleCode[6], 10);
                counteraction = parseInt(ruleCode[7], 10);
                ret[r] = new DrawRule(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction);
                break;
            case types.DRAW_UNTIL_RULE:
                trigger = parseInt(ruleCode[3], 10);
                target = parseInt(ruleCode[4], 10);
                target_can_be_cur = false;
                source = targets.DECK;
                target_card= parseInt(ruleCode[5], 10);
                target_suit = parseInt(ruleCode[6], 10);
                counteraction = parseInt(ruleCode[7], 10);
                ret[r] = new DrawUntilRule(card, suit, type, trigger, target, target_can_be_cur, source, target_card, target_suit, counteraction);
                break;
            case types.SKIP_PLAYER_RULE:
                trigger = parseInt(ruleCode[3], 10);
                target = parseInt(ruleCode[4], 10);
                target_can_be_cur = false;
                ret[r] = new SkipRule(card, suit, type, trigger, target, target_can_be_cur);
                break;
            case types.CHANGE_RULE_RULE:
                trigger = parseInt(ruleCode[3], 10);
                let rule = parseInt(ruleCode[4], 10);
                let newValue = parseInt(ruleCode[5], 10);
                ret[r] = new ChangeRuleRule(card, suit, type, trigger, rule, newValue);
                break;
            case types.VIEW_CARD_RULE:
                trigger = parseInt(ruleCode[3], 10);
                source = parseInt(ruleCode[4], 10);
                target_can_be_cur = false;
                num_cards = parseInt(ruleCode[5], 10);
                ret[r] = new ViewCardRule(card, suit, type, trigger, source, target_can_be_cur, num_cards);
                break;
            case types.JUMP_IN_RULE:
                trigger=triggers.PASSIVE;
                target_card= parseInt(ruleCode[3], 10);
                target_suit = parseInt(ruleCode[4], 10);
                ret[r] = new JumpInRule(card, suit, type, target_card, target_suit);
                break;
        }
    }
    return ret;
}