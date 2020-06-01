<html>

<head>
    <title>Ruleset - Decklab</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="assets/navbar/corgi_pixel.png">
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
    <link rel="stylesheet" href="<?php echo base_url('lobby_password/Lobby_Password.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('create_lobby/create_lobby.css'); ?>" />
    <script src="<?php echo base_url('base/Base.js'); ?>"></script>

</head>

<body><div class="header">
        <nav class="navbar bg-dark navbar-dark">
            <a class="navbar-brand">
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
                          echo "<a class='nav-link' href='".site_url('Controller/login_page')."'>Login</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url('UserController/register')."'>Register</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if (!$_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url('UserController/register')."'>Saved Decks</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($controller == "AdminController") {
                          echo "<a class='nav-link' href='".site_url('AdminController/registerAdmin')."'>Choose Admin</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if ($controller == "AdminController") {
                          echo "<a class='nav-link' href='".site_url('UserController/register')."'>Choose Highlighted Decks</a>";
                        }
                    ?>
                    </li>
                    <li class="nav-item">
                    <?php
                        if (!$_SESSION['user']->isGuest) {
                          echo "<a class='nav-link' href='".site_url('UserController/register')."'>Logout</a>";
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <container>
        <div class="form_class lobby_create_class">
            <form action = "<?= site_url("$controller/create_lobby_submit/{$idDeck}") ?>" method="post">
                <table>
                    <tr>
                        <td>
                            <!---enterlobby name-->
                            <input type="text" name = "lobby_name" class="form-control customInput mediumInput <?=  isset($error) ? 'has-error'  : ''   ?>" id="inputLobbyName" placeholder="Choose Lobby Name">
                            <?php
                                if ($error == "Empty lobby name field") {
                                    echo "<div class='error'>".$error."</div>";
                                }
                            ?>
                            <br>
                            <br>
                        </td>
                        <td>
                            <div id = "privateCheckboxDiv">
                                <input type="checkbox" name = "private_checkmark" id = "privateCheckbox"><label for="privateCheckbox" class="label_class" id = "privateCheckboxLabel">Private</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!---enter lobby password if private-->
                            <label for="inputLobbyPassword" class="label_class"> Lobby Password</label>
                            <input type="password" name = "create_lobby_password" class="form-control customInput mediumInput <?=  isset($error) ? 'has-error'  : ''   ?>" id="inputLobbyPassword" placeholder="Choose Lobby Password">
                            <?php
                                if ($error == "Empty lobby password field") {
                                    echo "<div class='error'>".$error."</div>";
                                }
                            ?>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!---max player count-->
                            <label for="inputMaxPlayerCount" class="label_class">Max Player Count</label>
                            <input type="number" name = "create_max_player_count" class="form-control customInput mediumInput numberInput <?=  isset($error) ? 'has-error'  : ''   ?>" id="inputMaxPlayerCount" value ="0">
                            <?php
                                if ($error == "Max Player Count out of range") {
                                    echo "<div class='error'>".$error."</div>";
                                }
                            ?>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!---select ruleset-->
                            <br>
                            <p class="label_class">Selected ruleset: </p>
                            <div id = "selected_ruleset_div">
                                <?php
                                    echo $deckName;
                                ?>
                            </div>
                            
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <!---finish button-->
                            <div id= "finish_lobby_button">
                                <input type="submit" name="create_lobby_submit_button" class="btn btn-primary" value="Finish">    
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </container>

</body>

</html>