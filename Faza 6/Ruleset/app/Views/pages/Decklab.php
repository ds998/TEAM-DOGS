<html>

<head>
    <title>Ruleset - Decklab</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/navbar/corgi_pixel.png'); ?>">
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

    <link rel="stylesheet" href="<?php echo base_url('decklab/Decklab.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" />
    <script src="<?php echo base_url('base/Base.js');?>"> </script>
    <script src="<?php echo base_url('game/Rule.js');?>"> </script>

    <script src="<?php echo base_url('decklab/Decklab_globals.js');?>"> </script>
    <script src="<?php echo base_url('decklab/Decklab_rules.js');?>"> </script>
    <script src="<?php echo base_url('decklab/decklab.js');?>"> </script>
</head>

<body>
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
                          echo "<a class='nav-link' href='".site_url("$controller/listUserDecks")."'>Saved Decks</a>";
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

    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-md-12 col-lg-3">
                <table class="table table-dark">
                    <tr class="topRow d-flex justify-content-between">
                        <th class="d-flex flex-nowrap align-items-center"><input id='clubsCB' type="checkbox" checked>
                            <img src="<?php echo base_url('/assets/decklab/clubs.png'); ?>" class="suit blackSuit"></th>
                        <th class="d-flex flex-nowrap align-items-center"><input id='diamondsCB' type="checkbox" checked>
                            <img src="<?php echo base_url('/assets/decklab/diamonds.png'); ?>" class="suit redSuit"></th>
                        <th class="d-flex flex-nowrap align-items-center"><input id='spadesCB' type="checkbox" checked>
                            <img src="<?php echo base_url('/assets/decklab/spades.png'); ?>" class="suit blackSuit"></th>
                        <th class="d-flex flex-nowrap align-items-center"><input id='heartsCB' type="checkbox" checked>
                            <img src="<?php echo base_url('/assets/decklab/hearts.png'); ?>" class="suit redSuit"></th>
                    </tr>
                </table>


                <table id="cardTable" class="table table-hover table-dark">
                    <thead>
                        <tr class="deckTableRowHeader topRow">
                            <th scope="col" class='numRulesCol'># of Rules</th>
                            <th scope="col">Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="deckTableRow addCard">
                            <th s cope="row" colspan="2" class="text-muted">+ Add card</th>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-hover table-dark">
                    <tr class="topRow d-flex globalRulesRow">
                        <th class="d-flex flex-nowrap align-items-center">Global Rules</th>

                    </tr>
                </table>
            </div>

            <div class="col-md-12 col-lg-9">
                <table id="ruleTable" class="table table-hover table-dark">
                    <thead>
                        <tr class="rulesTableRowHeader topRow rulesRow">

                            <th colspan="2" scope="col" class="">
                                <div class="d-flex align-items-center flex-fill">
                                    <div class="mr-auto rulesTitle">Rules</div>
                                    <input type="radio" name="ruleRadio" checked value="all">
                                    <div class="mr-1">All</div>
                                    <input type="radio" name="ruleRadio" value="Clubs"><img
                                        src="<?php echo base_url('assets/decklab/clubs.png'); ?>" class="suit blackSuit mr-1">
                                    <input type="radio" name="ruleRadio" value="Diamonds"><img
                                        src="<?php echo base_url('assets/decklab/diamonds.png'); ?>" class="suit redSuit mr-1">
                                    <input type="radio" name="ruleRadio" value="Spades"><img
                                        src="<?php echo base_url('assets/decklab/spades.png'); ?>" class="suit blackSuit mr-1">
                                    <input type="radio" name="ruleRadio" value="Hearts"><img
                                        src="<?php echo base_url('assets/decklab/hearts.png'); ?>" class="suit redSuit">
                                </div>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="addRuleRow rulesRow">
                            <th scope="row" colspan="2" class="text-muted">+ Add Rule</th>
                        </tr>
                    </tbody>
                </table>

                <form action="<?= site_url("$controller/decklab") ?>">
                    <textarea name="deckDecription" class="form-control customInput" rows="3" style="resize: none;"
                        maxlength="250" required placeholder="Enter a brief description of the ruleset..."></textarea>
                    <br>
                    <div class="sendForm">
                        <input type="text" required id="deck" name="cards" placeholder="Deck">
                        <input type="text" required id="suits" name="suits" placeholder="Suits">
                        <input type="text" id="rules" name="rules" placeholder="Rules">
                        <input type="text" required id="globalRules" name="globalRules" placeholder="GlobalRules">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-8" style="padding-bottom: 2px;"> <input id="deckName" name="deckName" type="text"
                                class="form-control customInput" required placeholder="Enter Ruleset Name"> </div>
                        <div class="col-sm-3 col-lg-2 d-flex" style="padding-bottom: 2px;"> <button type="submit"
                        class="btn btn-primary flex-fill" onclick="convertStoredInfo()">Save</button> </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>