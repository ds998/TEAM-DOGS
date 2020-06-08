<html>

<head>
    <title>Ruleset by team DOGS</title>
    <!-- Version: 1.0 -->
    <meta charset="UTF-8">
    <link rel ="shortcut icon" type="image/png" href="<?php echo base_url('assets/navbar/corgi_pixel.png'); ?>">
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


    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('main/Main.css'); ?>">

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
    <container>
        <div id="main_id">
            <div id = "highlighted_div">
                <table id = "table_deck">
                    <tr>
                        <?php foreach ($hdecks as $hdeck){
                            
                            echo "<td><a href = '".site_url("$controller/deckpreview/$hdeck->iddeck")."'><img id =".$hdeck->name." class = 'hd_image' width='150' height='150' src='".base_url('assets/navbar/dog_logo.png')."'></a></td>";
                            if($hdeck->orderNum%3==0&&$hdeck->orderNum!=0&&$hdeck->orderNum!=9)echo "</tr><tr>";    
                        }
                        ?>
                    </tr>
                </table>
                <p id = "table_title"></p>
            </div>
            <div id = "buttons_div">
                <a class="btn btn-primary btn-effect <?php if ($_SESSION['user']->isGuest)echo 'disabled'?>" role='button' href="<?php echo site_url("$controller/decklab"); ?>">Create Deck</a>
                <br>
                <a class="btn btn-primary btn-effect" href="<?php echo site_url("$controller/decklist"); ?>" role="button">List Decks</a>
                <br>
                <br>
                <br>
                <a class="btn btn-primary btn-effect" href="<?php echo site_url("$controller/all_lobbies"); ?>" role="button">Join Lobby</a>
                <br>
                <a class="btn btn-primary btn-effect" href="<?php echo site_url("$controller/decklist"); ?>" role="button">Create Lobby</a>
            </div>
        </div>
<!--
        <div class = "welcome_popup" id = "main_popup">
            <div id = "popup_contain">
                <h1>WELCOME</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                <button type="submit" class="btn btn-primary" onclick="closeForm()">Close</button>
            </div>
        </div>
-->

    <img id='byTeamDogs' src="<?php echo base_url('assets/navbar/teamDOGS.png'); ?>">

    </container>
    <script src="<?php echo base_url('main/Main.js'); ?>"></script>
    <script src="<?php echo base_url('base/Base.js'); ?>"></script>
</body>

</html>