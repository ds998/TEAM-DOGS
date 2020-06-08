const canvasSize = 3200;
const canvasH = 1800;

const CARD_W = 300;
const CARD_H = CARD_W * 1.5;
const SUIT_X = CARD_W / 6;
const CARD_M = CARD_W / 25;
const CARD_FONT_XL = CARD_W / 2;
const CARD_FONT_S = CARD_W / 5;
const CARD_HOVER = CARD_W / 13;
const HAND_Y = canvasH - CARD_H - CARD_HOVER;
const HAND_X = canvasSize / 2 - CARD_W / 2;
const HAND_W = CARD_W * 6;

const BGCOLOR = "#EEEEEE";

const suitSprites = [];
suitSprites['Clubs'] = new Image();
suitSprites['Clubs'].src = '../../assets/game/clubs.png';
suitSprites['Hearts'] = new Image();
suitSprites['Hearts'].src = '../../assets/game/hearts.png';
suitSprites['Spades'] = new Image();
suitSprites['Spades'].src = '../../assets/game/spades.png';
suitSprites['Diamonds'] = new Image();
suitSprites['Diamonds'].src = '../../assets/game/diamonds.png';

const skipImg = new Image();
skipImg.src = '../../assets/game/skip.png';
const bgImg = new Image();
bgImg.src = '../../assets/game/bg.png';
const cardHImg = new Image();
cardHImg.src = '../../assets/game/cardHover.png';
const cardBackImg = new Image();
cardBackImg.src = '../../assets/game/cardBack.png';
const cardBackHImg = new Image();
cardBackHImg.src = '../../assets/game/cardBackHover.png';
const cardBackAImg = new Image();
cardBackAImg.src = '../../assets/game/cardBackActive.png';
const cardImg = new Image();
cardImg.src = '../../assets/game/card.png';

function getLines(ctx, text, maxWidth) {
    var words = text.split(" ");
    var lines = [];
    var currentLine = words[0];

	var lineLen = ctx.measureText(currentLine).width;
		var part='';
		let letterCount=currentLine.length-4;
		while (lineLen >= maxWidth) {
			var part = currentLine.slice(0,letterCount);
			lineLen = ctx.measureText(part+'-').width;
			letterCount--;
		}
		if (part) {
			lines.push(	currentLine.slice(0,letterCount)+'-');
			currentLine='-'+currentLine.slice(letterCount);
		}
    for (var i = 1; i < words.length; i++) {
		var lineLen = ctx.measureText(currentLine).width;
		var rest='';
		let letterCount=4;
		while (lineLen >= maxWidth) {
			rest=currentLine.splice(currentLine.length-letterCount) + rest;
			lineLen = ctx.measureText(currentLine+'-').width;
		}
		if (rest) {
			lines.push(currentLine+'-');
			currentLine= '-' + rest;
		}
        var word = words[i];
		var width = ctx.measureText(currentLine + " " + word).width;
        if (width < maxWidth) {
            currentLine += " " + word;
        } else {
            lines.push(currentLine);
            currentLine = word;
        }
    }
    lines.push(currentLine);
    return lines;
}

function getXY(mouseX,mouseY){
    newX = mouseX * matrix[0] + mouseY * matrix[2] + matrix[4];
    newY = mouseX * matrix[1] + mouseY * matrix[3] + matrix[5];
    return({x:newX,y:newY});
}

class GraphicsManager {
	static enemyLocations = [
		[['up', 0, 1]],
		[['right', 0, 1], ['left', 0, 1]],
		[['right', 0, 1], ['up', 0, 1], ['left', 0, 1]],
		[['right', 0, 1], ['up', 0, 2], ['up', 1, 2], ['left', 0, 1]],
		[['right', 0, 1], ['up', 0, 3], ['up', 1, 3], ['up', 2, 3], ['left', 0, 1]],
		[['right', 0, 2], ['right', 1, 2], ['up', 0, 2], ['up', 1, 2], ['left', 0, 2], ['left', 1, 2]],
		[['right', 0, 2], ['right', 1, 2], ['up', 0, 3], ['up', 1, 3], ['up', 2, 3], ['left', 0, 2], ['left', 1, 2]],
		[['right', 0, 2], ['right', 1, 2], ['up', 0, 4], ['up', 1, 4], ['up', 2, 4], ['up', 3, 4], ['left', 0, 2], ['left', 1, 2]],
		[['right', 0, 2], ['right', 1, 2], ['up', 0, 5], ['up', 1, 5], ['up', 2, 5], ['up', 3, 5], ['up', 4, 5], ['left', 0, 2], ['left', 1, 2]]
	];

	constructor(controller) {
		this.controller = controller;

		this.canvas = document.getElementById('canvas');
		this.ctx = this.canvas.getContext('2d');

		this.scale = canvasSize / canvas.offsetWidth;
		this.resizeGame();

		this.lastX = 0;
		this.lastY = 0;

		this.deck = true;
		this.deckCard = new CardBackSprite();

		//Discard
		this.discardCard = null;
		this.discardX=1600;
		this.discardY=700;

		this.fps=30;
		setInterval(function () {
			this.draw();
		}.bind(this), Math.floor(1000/this.fps));

		this.cards = [];
		this.enemyCards = [];

		//Listeners
		this.mousemoveListener = this.updateLogic.bind(this);
		this.mouseupListener = this.click.bind(this);
		this.canvas.addEventListener("mousemove", this.mousemoveListener);
		this.canvas.addEventListener("click", this.mouseupListener);
		window.addEventListener('resize', this.resizeGame.bind(this), false);

		this.display=false;
		this.animPCq=[];
	}

	resizeGame() {
		var c = document.getElementById('canvas');
		var newWidth = c.offsetWidth;
	
		this.scale = canvasSize / newWidth;
	}

	click(e) {
		if (this.handelingClick) return;
		this.handelingClick=true;
		let x = e.offsetX * this.scale;
		let y = e.offsetY * this.scale;

		if (this.choose) {
			if (this.hovered != -1) {
				this.controller.chosenOne=this.controller.player.index % (this.controller.enemyPlayers.length)+this.hovered;
				this.enemyCards[this.hovered].setHover(false);
				this.hovered = -1;
				this.choose = false;
				this.overlay = false;
			}
		} else {
			if (this.hovered != -1) {
				switch (this.hovered) {
					case -2:
						if (this.deck) {
							this.controller.drawFromDeck();
						}
						break;
					default:
						if (this.animPCq.length == 0) {
							var selected = this.hovered;
							this.controller.tryToPlay(this.controller.cardAt(selected)).then((success) =>{
								if (success) {
									let pCard = this.cards.splice(selected, 1)[0];
									pCard.setHover(false);
									this.playAnimation(pCard);
									
									this.hovered = -1;
								}
							}); 
						}
						break;
				}
			}
		}
		this.handelingClick=false;
	}
	chooseCharacter() {
		this.overlay=true;
		this.choose=true;
	}

	newCard(name, suit) {
		this.cards.push(new CardSprite(name, suit));
	}

	updateLogic(e) {
		this.lastX = e.offsetX * this.scale;
		this.lastY = e.offsetY * this.scale;
	}

	updateActive(activePlayer) {
		let playerIndex = this.controller.player.index;
		
		if (activePlayer == playerIndex) activePlayer=this.enemyCards.length;
		else if (activePlayer > playerIndex) activePlayer--;
		for (let p=0; p<this.enemyCards.length; p++) {
			if (p == activePlayer) this.enemyCards[p].setActive(true);
			else this.enemyCards[p].setActive(false);
		}
	}

	draw() {
		this.hovered = -1;
		if (!this.overlay) {
			let hov = this.deckCard.isHover(this.lastX, this.lastY);
			this.deckCard.setEnlarge(hov);
			if (hov) this.hovered = -2;
			else {
				let c;
				for (c = this.cards.length - 1; c >= 0; c--) {
					hov = this.cards[c].isHover(this.lastX, this.lastY);
					this.cards[c].setHover(hov);
					if (hov) {
						this.hovered = c;
						break;
					}
				}
				c--;
				for (c; c >= 0; c--) {
					this.cards[c].setHover(false);
				}
			}
		}

		this.ctx.drawImage(bgImg, 0, 0, canvasSize, canvasH)
		if (!this.overlay && this.controller.myTurn()){
			this.ctx.fillStyle = "#F69D52";
			this.ctx.fillRect(HAND_X/2 - CARD_W/2, HAND_Y-CARD_HOVER, HAND_W+ CARD_W, CARD_H + 2*CARD_HOVER);
		}
		//this.ctx.fillStyle = BGCOLOR;
		//this.ctx.fillRect(0, 0, canvasSize, canvasH);

		if (this.deck) this.deckCard.draw(this.ctx, 1200, 700);
		if (this.discardCard) this.discardCard.draw(this.ctx, this.discardX, this.discardY);

		for (let c = 0; c < this.cards.length; c++) {
			let offsetX;
			if (this.cards.length > 5) {
				if (this.hovered > -1) {
					if (c > this.hovered) offsetX = ((c + 1) - (this.cards.length + 1) / 2) * CARD_W * (6 / this.cards.length) + (CARD_W - CARD_W * (6 / this.cards.length));
					else offsetX = ((c + 1) - (this.cards.length + 1) / 2) * CARD_W * (6 / this.cards.length);
				} else {
					offsetX = ((c + 1) - (this.cards.length + 1) / 2) * CARD_W * (6 / this.cards.length);
				}

			} else offsetX = ((c + 1) - (this.cards.length + 1) / 2) * CARD_W;
			this.cards[c].draw(this.ctx, HAND_X + offsetX, HAND_Y);
		}

		if(this.animPCq.length > 0) {
			this.animPCq[0].paX+=this.animPCq[0].incX;
			this.animPCq[0].paY+=this.animPCq[0].incY;
			this.animPCq[0].card.draw(this.ctx, this.animPCq[0].paX, this.animPCq[0].paY);

			if ( (Math.abs(this.discardX-this.animPCq[0].paX)<=Math.abs(this.animPCq[0].incX)+1) && (Math.abs(this.discardY-this.animPCq[0].paY)<=Math.abs(this.animPCq[0].incY)+1) ) {
				this.discardCard=this.animPCq[0].card;
				this.animPCq.shift();
			}
		}
		if (this.controller.skip) this.ctx.drawImage(skipImg, HAND_X , HAND_Y+CARD_H/2- (CARD_W-CARD_HOVER)/2, CARD_W-CARD_HOVER, CARD_W-CARD_HOVER);

		if (this.overlay) {
			this.ctx.fillStyle = "#23232399";
			this.ctx.fillRect(0, 0, canvasSize, canvasH);

			if (this.choose) {
				this.ctx.textAlign = "center";
				this.ctx.font = "200px Calibri";
				this.ctx.strokeStyle = 'black';
				this.ctx.lineWidth = 12;
				this.ctx.strokeText("Choose a player", canvasSize/2, canvasH/2);
				this.ctx.fillStyle = "#FFBB00";
				this.ctx.fillText("Choose a player", canvasSize/2, canvasH/2);
			} else if (this.display) {
				for(let c=0; c<this.displayCard.length;c++) {
					let offsetX = ((c + 1) - (this.displayCard.length + 1) / 2) * CARD_W;
					this.displayCard[c].draw(this.ctx, HAND_X + offsetX, canvasH/2);
				}
			} else if (this.gameOver) {
				this.ctx.textAlign = "center";
				this.ctx.font = "200px Calibri";
				this.ctx.strokeStyle = 'black';
				this.ctx.lineWidth = 12;
				this.ctx.strokeText("GAME ENDED", canvasSize/2, canvasH/2);
				this.ctx.fillStyle = "#FFBB00";
				this.ctx.fillText("GAME ENDED", canvasSize/2, canvasH/2);
			}
		}

		if (this.enemyCards.length==0) this.setEnemySprites();
		else {
			let enemies = this.controller.enemyPlayers;

			let startIndex = this.controller.player.index % (enemies.length);
			var index = startIndex;
			var count = 0;
			do {
				let info = GraphicsManager.enemyLocations[enemies.length-1][count];
				this.drawEnemy(info[1], info[2], index, info[0]);
				index= (index + 1) % (enemies.length);
				count++;
			} while (index != startIndex);
		}
		
	}

	playAnimation(card, startX=null, startY=null) {
		const time= 200;
		let animInfo = (card, startX, startY) => {
			let paX
			let paY
			if (startX) paX=startX;
			else paX=card.x;
			if (startY) paY=startY;
			else paY=card.y;
			let incX = (this.discardX-paX) / ((time/1000)*this.fps);
			let incY = (this.discardY-paY) / ((time/1000)*this.fps);

			return {
				'card': card,
				'paX': paX,
				'paY': paY,
				'incX': incX,
				'incY': incY
			}
		}
		

		this.animPCq.push(animInfo(card, startX, startY));
	}

	setEnemySprites() {
		let enemies = this.controller.enemyPlayers;

		let startIndex = this.controller.player.index % (enemies.length);
		var index = startIndex;
		var count = 0;
		do {
			this.enemyCards[index] = new CardBackSprite();
			let info = GraphicsManager.enemyLocations[enemies.length-1][count];
			this.drawEnemy(info[1], info[2], index, info[0]);
			index= (index + 1) % (enemies.length);
			count++;
		} while (index != startIndex);
	}

	drawEnemy(curP, maxP, index, position) {
		this.ctx.save();
		let rotation=0;
		let localX=this.lastX;
		let localY=this.lastY;
		let temp;
		switch (position) {
			case 'right':
				this.ctx.translate(canvasSize - CARD_H - CARD_HOVER, canvasH / 2);
				localX -= canvasSize - CARD_HOVER;
				localY -= canvasH / 2;
				temp = localX;
				localX = -localY;
				localY = -temp;
				rotation = -0.5;
				break;
			case 'left':
				this.ctx.translate(CARD_H + CARD_HOVER, canvasH / 2);
				localX -= CARD_HOVER;
				localY -= canvasH / 2;
				temp = localX;
				localX = localY;
				localY = temp;
				rotation = 0.5;
				break;
			case 'up':
				this.ctx.translate(canvasSize / 2, CARD_HOVER + CARD_H);
				localX -= canvasSize / 2;
				localY -= CARD_HOVER + CARD_H;
				localX = -localX;
				localY = -localY;
				rotation = 1;
				break;
		}
		this.ctx.rotate(Math.PI * rotation);
		

		let offsetX = ((maxP + 1)/ 2-(curP + 1)) * CARD_W * 3 / 2;

		this.enemyCards[index].setXY(0 - offsetX - CARD_W / 2, 0);
		if (this.choose) {
			let hov = this.enemyCards[index].isHover(localX, localY);
			this.enemyCards[index].setHover(hov);
			if (hov) this.hovered = index;
		}

		this.enemyCards[index].draw(this.ctx, 0 - offsetX - CARD_W / 2, 0);

		this.ctx.textAlign = "center";
		this.ctx.font = CARD_FONT_XL.toString(10) + "px Calibri";

		this.ctx.translate(- offsetX, CARD_H / 2);
		this.ctx.rotate(Math.PI * -rotation);
		if (rotation == Math.floor(rotation)) {
			this.ctx.strokeStyle = 'black';
			this.ctx.lineWidth = 12;
			this.ctx.strokeText(this.controller.enemyPlayers[index].cardCount, 0, 0);
			this.ctx.fillStyle = "#FFFFFF";
			this.ctx.fillText(this.controller.enemyPlayers[index].cardCount, 0, 0);

			this.ctx.font = CARD_FONT_S.toString(10) + "px Calibri";
			this.ctx.strokeStyle = 'black';
			this.ctx.lineWidth = 12;
			this.ctx.strokeText(this.controller.enemyPlayers[index].name, 0, -CARD_FONT_XL+CARD_HOVER);
			this.ctx.fillStyle = "#FFFFFF";
			this.ctx.fillText(this.controller.enemyPlayers[index].name, 0, -CARD_FONT_XL+CARD_HOVER);
		}
		else {
			this.ctx.strokeStyle = 'black';
			this.ctx.lineWidth = 12;
			this.ctx.strokeText(this.controller.enemyPlayers[index].cardCount, 0, CARD_FONT_XL/3);
			this.ctx.fillStyle = "#FFFFFF";
			this.ctx.fillText(this.controller.enemyPlayers[index].cardCount, 0, CARD_FONT_XL/3);

			this.ctx.font = CARD_FONT_S.toString(10) + "px Calibri";
			this.ctx.strokeStyle = 'black';
			this.ctx.lineWidth = 12;
			this.ctx.strokeText(this.controller.enemyPlayers[index].name, 0, CARD_FONT_XL/3-CARD_FONT_XL+CARD_HOVER);
			this.ctx.fillStyle = "#FFFFFF";
			this.ctx.fillText(this.controller.enemyPlayers[index].name, 0, CARD_FONT_XL/3-CARD_FONT_XL+CARD_HOVER);
		}
		this.ctx.restore();
	}

	async displayCards(cards) {
		this.overlay=true;
		this.display=true;
		this.displayCard=[];
		cards.forEach(card => {
			this.displayCard.push(new CardSprite(card.name, card.suit));
		});
		setTimeout(() => {
			this.overlay=false;
			this.display=false;
			this.displayCard=null;
		}, 6000);
	}

	endGame() {
		this.overlay = true;
		this.gameOver=true;
	}

	getEnemyCenter(n) {
		let info = GraphicsManager.enemyLocations[this.controller.enemyPlayers.length-1][n];

		let position=info[0];
		let curP=info[1];
		let maxP=info[2];

		let offsetX = ((maxP + 1)/ 2-(curP + 1)) * CARD_W * 3 / 2;

		let localX=0;
		let localY=0;
		switch (position) {
			case 'right':
				localX += canvasSize - CARD_HOVER - CARD_H/2- CARD_W/2;
				localY += (canvasH / 2) - CARD_H/2 - offsetX;
				break;
			case 'left':
				localX += CARD_HOVER + CARD_H/2 - CARD_W/2;
				localY += (canvasH / 2) - CARD_H/2 + offsetX;
				break;
			case 'up':
				localX += (canvasSize / 2) - offsetX - CARD_W/2;
				localY += CARD_HOVER;
				break;
		}

		return {'x':localX , 'y':localY}
	}
}

class CardSprite {
	constructor(name, suit) {
		this.name = name;
		this.suit = suit;
		this.hover = false;
		if (suit == 'Clubs' || suit == 'Spades') this.color = '#232323';
		else if (suit == 'Diamonds' || suit == 'Hearts') this.color = "#CD0F0F";
		else this.color = "#FFBB00";
	}

	setHover(val) {
		this.hover = val;
	}

	draw(ctx, x, y) {
		if (x == undefined || y == undefined) {
			x = this.x;
			y = this.y;
		} else {
			this.x = x;
			this.y = y;
		}

		if (this.hover) ctx.drawImage(cardHImg, x, y, CARD_W, CARD_H);
		else ctx.drawImage(cardImg, x, y, CARD_W, CARD_H);

		ctx.textAlign = "start";
		ctx.font = SUIT_X.toString(10) + "px Lucida Console";
		ctx.fillStyle = this.color;
		ctx.fillText(this.name.charAt(0), x + CARD_M, y + SUIT_X);
		ctx.drawImage(suitSprites[this.suit], x + CARD_M / 4, y + SUIT_X + CARD_M, SUIT_X, SUIT_X);

		ctx.save();
		ctx.translate(x + CARD_W / 2, y + CARD_H / 2);
		ctx.rotate(Math.PI * 1);
		ctx.translate(-x - CARD_W / 2, -y - CARD_H / 2);
		ctx.fillText(this.name.charAt(0), x + CARD_M, y + SUIT_X);
		ctx.drawImage(suitSprites[this.suit], x + CARD_M / 4, y + SUIT_X + CARD_M, SUIT_X, SUIT_X);
		ctx.restore();

		ctx.save();
		ctx.translate(x + CARD_W / 2, y + CARD_H / 2);
		ctx.rotate(Math.PI * 1.5);
		ctx.textAlign = "center";
		ctx.font = CARD_FONT_S.toString(10) + "px Calibri";
		let lines=getLines(ctx, this.name, CARD_H+CARD_HOVER);
		for (let l=0; l<lines.length; l++) {
			let offsetY = ((l + 1)-(lines.length + 1)/ 2) * CARD_FONT_S;
			ctx.fillText(lines[l], 0, CARD_FONT_S/3+offsetY);
		}
		
		ctx.restore();
	}

	isHover(x, y) {
		if (this.x == undefined || this.y == undefined) return false;
		if (x < this.x || x >= this.x + CARD_W) return false;
		if (y < this.y || y >= this.y + CARD_H) return false;
		return true;
	}
}

class CardBackSprite {
	constructor() {
		this.enlarge = false;
		this.hover = false;
		this.active = false;
		this.skip = false;
	}

	setXY(x, y) {
		this.x = x;
		this.y = y;
	}

	setEnlarge(val) {
		this.enlarge = val;
	}
	
	setHover(val) {
		this.hover = val;
	}

	setSkip(val) {
		this.skip=val;
	}

	setActive(val) {
		this.active = val;
	}

	draw(ctx, x, y) {
		this.x = x;
		this.y = y;
		if (this.enlarge) ctx.drawImage(cardBackImg, x - CARD_HOVER / 2, y - CARD_HOVER / 2, CARD_W + CARD_HOVER, CARD_H + CARD_HOVER);
		else if(this.active) ctx.drawImage(cardBackAImg, x, y, CARD_W, CARD_H);
		else ctx.drawImage(cardBackImg, x, y, CARD_W, CARD_H);
		if (this.hover) ctx.drawImage(cardBackHImg, x, y, CARD_W, CARD_H);
		if(this.skip) ctx.drawImage(skipImg, x+CARD_HOVER/2, y, CARD_W-CARD_HOVER, CARD_W-CARD_HOVER);
	}

	isHover(x, y) {
		if (x < this.x || x >= this.x + CARD_W) return false;
		if (y < this.y || y >= this.y + CARD_H) return false;
		return true;
	}
}