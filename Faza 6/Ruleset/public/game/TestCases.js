function setup() {
    var rules = [{
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
            cardMatcher: new CardMatcher('', 'Diamonds'),
            trigger: triggers.ON_PLAY,
            type: types.DRAW_RULE,
            detail: {
                target: targets.NEXT,
                target_can_be_cur: false,
                source: targets.DECK,
                num_cards: 2
            }
        },
        {
            cardMatcher: new CardMatcher('2', 'Diamonds'),
            trigger: triggers.ON_PLAY,
            type: types.DRAW_UNTIL_RULE,
            detail: {
                target: targets.NEXT,
                target_can_be_cur: false,
                source: targets.DECK,
                target_card: new CardMatcher('', 'Diamonds')
            }
        }
    ];
}

if (document.readyState == 'loading') {
    document.addEventListener('load', setup());
} else {
    setup();
}


var cm;

function resizeGame() {
    var c = document.getElementById('canvas');
    var newWidth = c.offsetWidth;

    if (cm != null) {
        cm.scale = canvasSize / newWidth;
        cm.draw();
    }
}