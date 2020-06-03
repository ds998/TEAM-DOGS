class Rule {
    constructor(card, suit, type, trigger) {
        this.cardMatcher = new CardMatcher(card, suit);
        this.type = type;
        this.trigger = trigger;
        this.detail = {};
    }
}

class DrawRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction) {
        super(card, suit, type, trigger);
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
        this.detail.source = source;
        this.detail.num_cards = num_cards;
        this.detail.counteraction = counteraction;
    }
}

class DrawUntilRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur, source, target_card, target_suit, counteraction) {
        super(card, suit, type, trigger);
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
        this.detail.source = source;
        let cardMissMatch=false;
        let suitMissMatch=false;
        if (target_card == 'a') target_card = '';
        else if (target_card == 'd') {
            target_card = '';
            cardMissMatch=true;
        }
        if (target_suit == 'a') target_suit = '';
        else if (target_suit == 's') target_suit = suit;
        else if (target_suit == 'd') {
            target_suit = '';
            suitMissMatch=true;
        }
        this.detail.matcher = new CardMatcher(target_card, target_suit, cardMissMatch, suitMissMatch);
        this.detail.counteraction = counteraction;
        this.detail.send = target_card+'-'+target_suit;
    }
}

class SkipRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur) {
        super(card, suit, type, trigger);
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
    }
}

class ChangeRuleRule extends Rule {
    constructor(card, suit, type, trigger, rule, newValue) {
        super(card, suit, type, trigger);
        this.detail.rule = rule;
        this.detail.newValue = newValue;
    }
}

class ViewCardRule extends Rule {
    constructor(card, suit, type, trigger, source, target_can_be_cur, num_cards) {
        super(card, suit, type, trigger);
        this.detail.source = source;
        this.detail.target_can_be_cur = target_can_be_cur;
        this.detail.num_cards = num_cards;
    }
}

class JumpInRule extends Rule {
    constructor(card, suit, type, trigger, target_card, target_suit) {
        super(card, suit, type, trigger);
        let cardMissMatch=false;
        let suitMissMatch=false;
        if (target_card == 'a') target_card = '';
        else if (target_card == 'd') {
            target_card = '';
            cardMissMatch=true;
        }
        if (target_suit == 'a') target_suit = '';
        else if (target_suit == 's') target_suit = suit;
        else if (target_suit == 'd') {
            target_suit = '';
            suitMissMatch=true;
        }
        this.detail.matcher = new CardMatcher(target_card, target_suit, cardMissMatch, suitMissMatch);
    }
}