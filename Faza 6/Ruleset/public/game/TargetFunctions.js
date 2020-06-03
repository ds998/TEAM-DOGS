const TargetFunctions = [];

TargetFunctions[targets.NEXT] = function (controller, rule, event) {
    return controller.getNextPlayer(event.player);
}

TargetFunctions[targets.PREV] = function (controller, rule, event) {
    return controller.getPreviousPlayer(event.player);
}

TargetFunctions[targets.CHOOSE] = function (controller, rule, event) {
    return controller.chooseOther();
}

TargetFunctions[targets.RANDOM] = async function (controller, rule, event) {
    return await controller.randomPlayer(rule.detail.target_can_be_cur);
}

TargetFunctions[targets.CURRENT] = function (controller, rule, event) {
    return event.player.id;
}

TargetFunctions[targets.DECK] = function (controller, rule, event) {
    return controller.deck.id;
}

TargetFunctions[targets.DISCARD_PILE] = function (controller, rule, event) {
    return -1;
}