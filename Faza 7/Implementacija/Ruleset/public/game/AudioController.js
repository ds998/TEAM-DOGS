class AudioController {
    constructor() {
        //this.bgMusic = new Audio("../../assets/game/Psychonauts - Napoleon's Final Conflict Revisited.mp3");
        //this.splashSound = new Audio('../../assets/game/watersplash.flac');
        //this.explosion0 = new Audio('../../assets/game/explode.wav');
        //this.explosion1 = new Audio('../../assets/game/explodemini.wav');
        this.click = new Audio('../../assets/game/click.wav');
        //this.victorySound = new Audio("../../assets/game/Small Item Get - The Legend of Zelda Twilight Princess.mp3");

        this.drawSound = new Audio("../../assets/game/cardPlace1.wav");
        
        this.place0 = new Audio("../../assets/game/cardPlace2.wav");
        this.place1 = new Audio("../../assets/game/cardPlace3.wav");
        this.place2 = new Audio("../../assets/game/cardPlace4.wav");

        //this.delete0 = new Audio("../../assets/game/dull_metal_collision_11.wav");
        //this.delete1 = new Audio("../../assets/game/dull_metal_collision_12.wav");

        //this.bgMusic.volume = 0.5;
        //this.bgMusic.loop = true;
    }


    // startMusic() {
    //     this.bgMusic.play();
    // }
    // stopMusic() {
    //     this.bgMusic.pause();
    //     this.bgMusic.currentTime = 0;
    // }
    draw() {
        this.drawSound.play().catch(error => {});
    }
    // splash() {
    //     this.splashSound.play().catch(error => {
    // Autoplay was prevented.
    // Show a "Play" button so that user can start playback.
    //});
    // }

    place() {
        switch (Math.floor(Math.random() * 3)) {
            case 0:
                this.place0.play().catch(error => {});
                break;
            case 1:
                this.place1.play().catch(error => {});
                break;
            case 2:
                this.place2.play().catch(error => {});
                break;
        }
    }

    // delete() {
    //     var deleteSound;
    //     if (Math.floor(Math.random() * 2)) deleteSound = this.delete0.cloneNode();
    //     else deleteSound = this.delete1.cloneNode();
    //     deleteSound.play().catch(error => {
    // Autoplay was prevented.
    // Show a "Play" button so that user can start playback.
    //});
    // }

    playClick() {
        this.click.play().catch(error => {});
    }

    // victory() {
    //     this.stopMusic();
    //     this.victorySound.play();
    // }
}