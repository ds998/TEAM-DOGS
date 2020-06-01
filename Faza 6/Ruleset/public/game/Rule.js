class CardMatcher {
    constructor(value, suit = '', valMissMatch=false, suitMissMatch=false) {
        this.value = value;
        this.suit = suit;
        this.valMissMatch=valMissMatch;
        this.suitMissMatch=suitMissMatch;
    }

    isMatch(card) {
        if (valMissMatch && this.value == card.value) return false;
        else if (this.value != '' && this.value != card.value) return false;
        
        if (suitMissMatch && this.suit == card.suit) return false;
        else if (this.suit != '' && this.suit != card.suit) return false;

        return true;
    }

    toString() {
        if (this.suit.length == 0) return this.value;
        else if (this.value.length == 0) return this.suit;
        else return this.value + ' of ' + this.suit;
    }
}

types = {
    DRAW_RULE: 1,
    DRAW_UNTIL_RULE: 2,
    SKIP_PLAYER_RULE: 3,
    CHANGE_RULE_RULE: 4,
    VIEW_CARD_RULE: 5,
    JUMP_IN_RULE: 6
}

triggers = {
    PASSIVE: 0,
    ON_PLAY: 1,
    ON_DRAW: 2,
    ON_DISCARD: 3
}

targets = {
    NEXT: 1,
    PREV: 2,
    CHOOSE: 3,
    RANDOM: 4,
    CURRENT: 5,
    DECK: 6,
    DISCARD_PILE: 7
}