function setup() {
    var rules = [
        {
            cardMatcher: new CardMatcher('7'),
            trigger: triggers.ON_PLAY,
            type: types.DRAW_RULE,
            detail: {
                target: targets.NEXT,
                target_can_be_cur: false,
                source: targets.DECK,
                num_cards: 3
            }
        },
        {
            cardMatcher: new CardMatcher('','Diamonds'),
            trigger: triggers.ON_PLAY,
            type: types.DRAW_RULE,
            detail: {
                target: targets.NEXT,
                target_can_be_cur: false,
                source: targets.DECK,
                num_cards: 2
            }
        }
    ];
    var deck_template = {
        num: 2,
        values: Deck.defaultValues,
        suits: Deck.defaultSuits,
        type: Deck.types.SHUFFLE_DISCARD
    }

    var controller = Controller.getController(2, rules, deck_template);

    controller.dealToAllPlayers(3, false);
    controller.printHands();
}

if (document.readyState == 'loading') {
    document.addEventListener('load', setup());
} else {
    setup();
}