var selected = null;
var selectedIndex = null;
var selectedInput = null;
var cardTableRef;
var ruleTableRef;
var cardID = 0;
var deck;
var globalRules;
var suits = ['Clubs', 'Diamonds', 'Spades', 'Hearts'];

const MAX_STARTING_DECK=5;
const MAX_HAND_LIMIT = 30;
const MAX_DRAW_CARDS = 30;
const MAX_VIEW_CARDS = 10;
const MAX_STARTING_HAND = 30;
const MAX_CARDS_PLAYED = 99;

const MIN_NUM_PLAYERS = 2;
const MAX_NUM_PLAYERS = 8;