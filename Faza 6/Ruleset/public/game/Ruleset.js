class Ruleset {
    constructor(rules, controller) {
        this.rules = rules;
        this.myController = controller;

        this.onPlayMap = {};
        this.onDrawMap = {};
        this.onDiscardMap = {};
    }

    addEventHandlers() {
        this.rules.forEach(rule => {
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

        player.draw(rule.detail.num_cards, source);
    }

    static drawUntilRule(controller, rule, event) {
        if (rule.type != types.DRAW_UNTIL_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](controller, rule, event);
        let source = TargetFunctions[rule.detail.source](controller, rule, event);

        let notDone = true;
        while (notDone) {
            let newCard = player.drawAndReturn(1, source)[0];
            if (newCard == null || rule.detail.target_card.isMatch(newCard)) notDone = false;
        }
    }
}