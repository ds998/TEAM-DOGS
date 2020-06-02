<html>

<head>
    <title>Ruleset - Login</title>
    <!-- Version: 1.0 -->
    <meta charset="UTF-8">
    <link rel ="shortcut icon" type="image/png" href="../assets/navbar/corgi_pixel.png">
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
    <link rel="stylesheet" href="<?php echo base_url('login/Login.css'); ?>" />
    <script src="<?php echo base_url('base/Base.js'); ?>"></script>
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
    <container>
        <div class="form_class">
            <form name = "login_form" action = "<?= site_url("$controller/login_submit") ?>" method="post">
                <br>
                <div class="form-group">
                    <label for="inputUsername" class="label_class">Username</label>
                    <input type="text" name = "user_name" class="form-control customInput mediumInput <?=  isset($error) ? 'has-error'  : ''   ?>" id="inputUsername" placeholder="Enter Username">
                    <?php
                        if (strpos($error, "empty") !== false && strpos($error, "username") !== false) {
                            echo "<div class='error'>Empty username field</div>";
                        }
                        if (strpos($error, "exist") !== false) {
                            echo "<div class='error'>User doesn't exist</div>";
                        }
                    ?>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="label_class">Password</label>
                    <input type="password" name = "user_password" class="form-control customInput mediumInput <?=  isset($error) ? 'has-error'  : ''   ?>" id="inputPassword" placeholder="Enter Password">
                    <?php
                        if (strpos($error, "empty") !== false && strpos($error, "password") !== false) {
                            echo "<div class='error'>Empty password field</div>";
                        }
                        else {
                            echo "<div class='error'>".$error."</div>";
                        }
                    ?>
                </div>
                <div class="form-row login-area">
                    <input type="submit" name="login_submit_button" class="btn btn-primary" value="Login">    
                </div>
                <small id="needAccount" class="form-text text-muted">Don't have an account?
                    <a id = "register_link" href="<?php echo site_url("$controller/register"); ?>">REGISTER</a>
                </small>
            </form>
        </div>
    </container>
</body>

</html>