<html>

<head>
    <title>Ruleset - Register</title>
    <meta charset="UTF-8">
    <link rel ="shortcut icon" type="image/png" href="assets/navbar/corgi_pixel.png">
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
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <link rel="stylesheet" href="Navbar.css" />
    <link rel="stylesheet" href="Login.css" />
</head>

<body>
    <container>
        <div class="col-sm-8 offset-sm-2 col-md-6 offset-md-3">
            <form action="<?= site_url("usercontroller/register/{}") ?>">
                <br>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" aria-describedby="emailHelp"
                        placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                        else.</small>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" aria-describedby="emailHelp"
                        placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" pattern=".{8,}" required title="Password needs to be at least 8 characters"
                        class="form-control" name="password" placeholder="Password">
                    <small name="passwordHelp" class="form-text text-muted">Password needs to be at least 8
                        characters</small>
                </div>

                <br>
                <div class="text-center">
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </div>

                <small id="haveAccount" class="form-text text-muted">Already have an account? <a
                        href="login.html">LOGIN</a></small>
                            
            </form>
        </div>
    </container>
</body>

</html>