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
    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('login/Login.css'); ?>" />
</head>

<body>
    <container>
        <div class="col-sm-8 offset-sm-2 col-md-6 offset-md-3">
            <form action="<?= site_url("admincontroller/registeradmin/{}") ?>">
                <br>
                <div class="form-group">
                    <label for="userID">userID</label>
                    <input type="text" class="form-control" name="userID" aria-describedby="emailHelp"
                        placeholder="Enter userID">
                </div>

                <br>
                <div class="text-center">
                    <button type="submit" name="register" class="btn btn-primary">Register Admin</button>
                </div>
                            
            </form>
        </div>
    </container>
</body>

</html>