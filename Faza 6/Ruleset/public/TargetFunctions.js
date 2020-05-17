const TargetFunctions = [];

TargetFunctions[targets.NEXT] = function (rule, event) {
    return Controller.getController().getNextPlayer(event.player);
}

TargetFunctions[targets.PREV] = function (rule, event) {
    return Controller.getController().getPreviousPlayer(event.player);
}

TargetFunctions[targets.CHOOSE] = function (rule, event) {
    return event.player.choosePlayer(rule.detail.target_can_be_cur);
}

TargetFunctions[targets.RANDOM] = function (rule, event) {
    return Controller.getController().randomPlayer(rule.detail.target_can_be_cur);
}

TargetFunctions[targets.CURRENT] = function (rule, event) {
    return event.player;
}

TargetFunctions[targets.DECK] = function (rule, event) {
    return Controller.getController().deck;
}

TargetFunctions[targets.DISCARD_PILE] = function (rule, event) {
    return Controller.getController().discardPile;
}