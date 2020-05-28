class Ruleset {
    constructor(rules) {
        this.rules = rules;
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
        if (event.card.name in this.onPlayMap) this.onPlayMap[event.card.name].forEach(handler => {
            handler(event);
        });
        // Handle events matching value
        if (event.card.value in this.onPlayMap) this.onPlayMap[event.card.value].forEach(handler => {
            handler(event);
        });
        // Handle events matching suit
        if (event.card.suit in this.onPlayMap) this.onPlayMap[event.card.suit].forEach(handler => {
            handler(event);
        });
    }

    handleOnDrawEvent(event) {
        // Handle events matching exact card
        if (event.card.name in this.onDrawMap) this.onDrawMap[event.card.name].forEach(handler => {
            handler(event);
        });
        // Handle events matching value
        if (event.card.value in this.onDrawMap) this.onDrawMap[event.card.value].forEach(handler => {
            handler(event);
        });
        // Handle events matching suit
        if (event.card.suit in this.onDrawMap) this.onDrawMap[event.card.suit].forEach(handler => {
            handler(event);
        });
    }

    generateEventHandler(rule) {
        switch (rule.type) {
            case types.DRAW_RULE:
                return (event) => {
                    Ruleset.drawFromRule(rule, event);
                };
        }
    }

    static drawFromRule(rule, event) {
        if (rule.type != types.DRAW_RULE) throw 'Error: Rule and handler missmatch!';
        let player = TargetFunctions[rule.detail.target](rule, event);
        let source = TargetFunctions[rule.detail.source](rule, event);

        player.draw(rule.detail.num_cards, source);
    }
}