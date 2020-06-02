class Rule {
    constructor(card, suit, type) {
        this.cardMatcher = new CardMatcher(card, suit);
        this.type = type;
    }
}

class DrawRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur, source, num_cards, counteraction) {
        super(card, suit, type);
        this.detail.trigger = trigger;
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
        this.detail.source = source;
        this.detail.num_cards = num_cards;
        this.detail.counteraction = counteraction;
    }
}

class DrawUntilRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur, source, target_card, target_suit, counteraction) {
        super(card, suit, type);
        this.detail.trigger = trigger;
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
        this.detail.source = source;
        this.detail.target_card = target_card;
        this.detail.target_suit = target_suit;
        this.detail.counteraction = counteraction;
    }
}

class SkipRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur) {
        super(card, suit, type);
        this.detail.trigger = trigger;
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
    }
}

class ChangeRuleRule extends Rule {
    constructor(card, suit, type, trigger, rule, newValue) {
        super(card, suit, type);
        this.detail.trigger = trigger;
        this.detail.rule = rule;
        this.detail.newValue = newValue;
    }
}

class ViewCardRule extends Rule {
    constructor(card, suit, type, trigger, target, target_can_be_cur, num_cards) {
        super(card, suit, type);
        this.detail.trigger = trigger;
        this.detail.target = target;
        this.detail.target_can_be_cur = target_can_be_cur;
        this.detail.num_cards = num_cards;
    }
}

class JumpInRule extends Rule {
    constructor(card, suit, type, target_card, target_suit) {
        super(card, suit, type);
        this.detail.target_card = target_card;
        this.detail.target_suit = target_suit;
    }
}