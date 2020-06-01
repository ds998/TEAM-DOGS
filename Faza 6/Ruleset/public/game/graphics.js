const canvasSize=3200;
const canvasH=1800;

const CARD_W=300;
const CARD_H=CARD_W*1.5;
const SUIT_X=CARD_W/6;
const CARD_M=CARD_W/25;
const CARD_FONT_S=CARD_W/10;
const CARD_HOVER=CARD_W/13;
const HAND_Y = canvasH-CARD_H-CARD_HOVER;
const HAND_X = canvasSize/2 - CARD_W/2;
const HAND_W = CARD_W*6;

const BGCOLOR="#EEEEEE";

const suitSprites = [];
suitSprites['Clubs'] = new Image();
suitSprites['Clubs'].src='../assets/game/clubs.png';
suitSprites['Hearts'] = new Image();
suitSprites['Hearts'].src='../assets/game/hearts.png';
suitSprites['Spades'] = new Image();
suitSprites['Spades'].src='../assets/game/spades.png';
suitSprites['Diamonds'] = new Image();
suitSprites['Diamonds'].src='../assets/game/diamonds.png';

const cardHImg = new Image();
cardHImg.src = '../assets/game/cardHover.png';
const cardBackImg = new Image();
cardBackImg.src = '../assets/game/cardBack.png';
const cardImg = new Image();
cardImg.src = '../assets/game/card.png';

class GraphicsManager {
	constructor(controller) {
		this.controller = controller;

		this.canvas = document.getElementById('canvas');
		this.ctx = this.canvas.getContext('2d');
		
		this.scale = canvasSize/canvas.offsetWidth;
		resizeGame();

		this.lastX = 0;
		this.lastY = 0;
		
		this.deck=true;
		this.deckCard = new CardBackSprite();
		setInterval(function () {
            this.draw();
		}.bind(this), 33);
		
		this.cards = [];

		//Listeners
		this.mousemoveListener = this.updateLogic.bind(this);
		this.mouseupListener = this.click.bind(this);
		this.canvas.addEventListener("mousemove", this.mousemoveListener);
		this.canvas.addEventListener("mouseup", this.mouseupListener);
	}

	click(e) {
		let x = e.offsetX*this.scale;
		let y = e.offsetY*this.scale;
		
		if (this.hovered != -1) {
			switch(this.hovered) {
				case -2:
					if (this.deck) {
						this.controller.drawFromDeck();
					}
					break;
			}
		}
	}

	newCard(name, suit) {
		let len=this.cards.push(new CardSprite(name, suit));
		this.cards[len-1].draw(this.ctx, HAND_X, HAND_Y);
	}

	updateLogic(e) {
		this.lastX = e.offsetX*this.scale;
        this.lastY = e.offsetY*this.scale;
	}

	draw() {
		this.hovered=-1;
		let hov = this.deckCard.isHover(this.lastX, this.lastY);
		this.deckCard.setEnlarge(hov);

		if (hov) this.hovered=-2;
		else {
			for(let c=0; c<this.cards.length; c++) {
				hov = this.cards[c].isHover(this.lastX, this.lastY);
				if (hov) this.hovered=c;
				this.cards[c].setHover(hov);
			}
		}

		this.ctx.fillStyle = BGCOLOR;
		this.ctx.fillRect(0, 0, canvasSize, canvasH);

		if (this.deck) this.deckCard.draw(this.ctx, 800, 600);
		
		for (let c=0; c<this.cards.length; c++) {
			let offsetX;
			if (this.cards.length>6) {
				if (hovered != -1) {
					offsetX = ((c+1)-(this.cards.length+1)/2) * CARD_W;
				}
				offsetX = ((c+1)-(this.cards.length+1)/2) * CARD_W;

			} else offsetX = ((c+1)-(this.cards.length+1)/2) * CARD_W;
			this.cards[c].draw(this.ctx, HAND_X+offsetX, HAND_Y);
		}
		
	}
}

class CardSprite {
constructor(name, suit) {
		this.name = name;
		this.suit = suit;
		this.hover=false;
		if (suit == 'Clubs' || suit == 'Spades') this.color='#232323';
		else if (suit == 'Diamonds' || suit == 'Hearts') this.color="#CD0F0F";
		else this.color="#FFBB00";
	}

	setHover(val) {
		this.hover=val;
	}

	draw(ctx, x, y) {
		if (x == undefined || y==undefined) {
			x=this.x;
			y=this.y;
		} else {
			this.x=x;
			this.y=y;
		}

		if (this.hover) ctx.drawImage(cardHImg, x, y, CARD_W, CARD_H);
		else ctx.drawImage(cardImg, x, y, CARD_W, CARD_H);

		ctx.font = SUIT_X.toString(10)+"px Lucida Console";
		ctx.fillStyle = this.color;
		ctx.fillText(this.name.charAt(0), x+CARD_M, y+SUIT_X);
		ctx.drawImage(suitSprites[this.suit], x+CARD_M, y+SUIT_X+CARD_M, SUIT_X, SUIT_X);

		ctx.save();
        ctx.translate(x + CARD_W / 2, y + CARD_H / 2);
		ctx.rotate(Math.PI * 1);
		ctx.translate(-x - CARD_W / 2, -y - CARD_H / 2);
		ctx.fillText(this.name.charAt(0), x+CARD_M, y+SUIT_X);
		ctx.drawImage(suitSprites[this.suit], x+CARD_M, y+SUIT_X+CARD_M, SUIT_X, SUIT_X);
		ctx.restore();
		
		ctx.save();
        ctx.translate(x + CARD_W / 2, y + CARD_H / 2);
		ctx.rotate(Math.PI * 1.5);
		ctx.textAlign = "center";
		ctx.font = CARD_FONT_S.toString(10)+"px Calibri";
		ctx.fillText(this.name, 0, 0);
        ctx.restore();
	}

	isHover(x, y) {
		if (x<this.x || x>=this.x+CARD_W) return false;
		if (y<this.y || y>=this.y+CARD_H) return false;
		return true;
	}
}

class CardBackSprite {
	constructor() {
		this.enlarge=false;
	}

	setEnlarge(val) {
		this.enlarge=val;
	}

	draw(ctx, x, y) {
		this.x=x;
		this.y=y;
		if(this.enlarge) ctx.drawImage(cardBackImg, x-CARD_HOVER/2, y-CARD_HOVER/2, CARD_W+CARD_HOVER, CARD_H+CARD_HOVER);
		else ctx.drawImage(cardBackImg, x, y, CARD_W, CARD_H);
	}

	isHover(x, y) {
		if (x<this.x || x>=this.x+CARD_W) return false;
		if (y<this.y || y>=this.y+CARD_H) return false;
		return true;
	}
}