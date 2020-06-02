<html>

<head>
    <title>Ruleset - Decklab</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('navbar/corgi_pixel.png'); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('deck_preview/deckPreview.css'); ?>"/>
    <script src="<?php echo base_url('base/Base.js'); ?>"></script>
    <script src="<?php echo base_url('deck_preview/deckPreview.js'); ?>"></script>
</head>

<body class="">
    <div class="header">
        <nav class="navbar bg-dark navbar-dark"> <!-- navbar-expand-lg -->
            <a class="navbar-brand" href="<?php echo site_url("$controller");?>">
                <img id='logoRuleImage' src="<?php echo base_url('assets/navbar/rule_icon.png'); ?>" alt="Logo" class='logoImage'>
                <img id='logoSetImage' src="<?php echo base_url('assets/navbar/set_icon.png'); ?>" alt="Logo" class='logoImage'>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarElements"
                aria-controls="navbarElements" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarElements">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                    <?php
                        if ($_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/login_page")."'>Login</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/register")."'>Register</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if (!$_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/register")."'>Saved Decks</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($controller == "AdminController") {
                          echo "<a class='nav-link' href='".site_url("$controller/registerAdmin")."'>Choose Admin</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($controller == "AdminController") {
                          echo "<a class='nav-link' href='".site_url("$controller/changeHD")."'>Choose Highlighted Decks</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if (!$_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url("$controller/logout")."'>Logout</a>";
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="welcome_popup" id="main_popup">
        <div id="deckPopup">
            <div id="testTest">
                <div id="deckTitleDiv">
                    <h1 id="deckName"><?php echo $deck->name ?></h1>
                    <h4 id="deckCreatorName">by Cezanne39</h4>
                </div>

                <div id="deckInfoCol">
                    <p id="deckDescription"><?php echo $deck->descr ?>
                    </p>
                    <h5>Played by <?php echo $deck->numberOfPlays ?> player/s</h5>
                    <h5>Average rating of <?php echo $deck->Rating ?>/5</h5>
                </div>



                <table id="deckCardsTable" class="table table-dark table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">All Cards</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $deckNames = explode(",", $deck->Cards);
                            foreach ($deckNames as $names) {
                                echo "
                                <tr scope='row' class='cardNameDamjanDerpe'> 
                                    <th>{$names}</th>
                                </tr>
                                    ";
                            }
                            ?>
                    </tbody>
                </table>

                <div id="deckRuleButtonCol">
                <!--
                    <table id="deckRulesTable" class="table table-dark table-bordered">
                        <thead>
                            <tr>
                                <th id="cardRowHeader" scope="col">Cards</th>
                                <th id="rulesRowHeader" scope="col">Rules</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th scope="row" class="cardName">Ace</th>
                                <td> <b>On Play:</b> Next player draws 1 card/s.<br /><b>On
                                        Draw:</b>
                                    Player
                                    who
                                    used this card looks at the top 3 cards of the Deck.
                                </td>
                            </tr>
                            <tr>
                                <th class="cardName">Ace <img src="../assets/decklab/clubs.png" class="suit blackSuit">
                                </th>
                                <td> <b>On Play:</b> Random player draws 5 card/s.</td>
                            </tr>
                            <tr>
                                <th scope="row" class="cardName">7</th>
                                <td> <b>On Play:</b> Next player draws 3 card/s.
                                </td>
                            </tr>
                            <tr>
                                <th class="cardName">10</th>
                                <td> Can jump-in on any 10 of any suit.</td>
                            </tr>
                            <tr>
                                <th scope="row" class="cardName">Jack</th>
                                <td> <b>On Play:</b> Pick the next suit.</td>
                            </tr>
                            <tr>
                                <th class="cardName">Awed Koigfd Flis</th>
                                <td> <b>On Play:</b> Change rule: Reverse order of play.</td>
                            </tr>
                        </tbody>
                    </table>
                -->

                    <div class="d-flex flex-row-reverse">
                        <a class="btn btn-primary" href="<?php echo base_url("$controller/create_lobby_page/$deck->idDeck") ?>" role="button">Play</a>
                        <a class="btn btn-primary" href="<?php if(!$user->isGuest)echo base_url("$controller/share_a_deck/$deck->idDeck") ?>" role="button">Share</a>
                        <a class="btn btn-primary" href="<?php echo base_url("$controller/save_user_deck/$user->idUser/$deck->idDeck") ?>" role="button">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>