<html>

<head>
    <title>Ruleset - Decklab</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('navbar/corgi_pixel.png'); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <link rel="stylesheet" href="<?php echo base_url('base/Base.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('base/Navbar.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('deck_list/deckList.css'); ?>"/>
    <script src="<?php echo base_url('base/Base.js'); ?>"></script>
    <script src="<?php echo base_url('deck_list/deckList.js'); ?>"></script>
</head>

<body>
    <div class="header">
        <nav class="navbar bg-dark navbar-dark"> <!-- navbar-expand-lg -->
            <a class="navbar-brand" href="../main/main.html">
                <img id='logoRuleImage' src="../assets/navbar/rule_icon.png" alt="Logo" class='logoImage'>
                <img id='logoSetImage' src="../assets/navbar/set_icon.png" alt="Logo" class='logoImage'>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarElements"
                aria-controls="navbarElements" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarElements">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../decklab/decklab.html">Create Ruleset</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="../deck_list/deckList.html">Find Ruleset</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login/login.html">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../register/register.html">Register</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <table id="deckTable" class="table table-hover table-dark table-striped">
                    <thead>
                        <tr class="deckTableRowHeader topRow">
                            <th scope="col">Name</th>
                            <th scope="col">Creator</th>
                            <th scope="col">Rating</th>
                            <th scope="col"># of Ratings</th>
                            <th scope="col"># Saves</th>
                            <th scope="col"># Plays</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="deckTableRow addCard">
                            <td>MauMau</td>
                            <td>Cezanne38</td>
                            <td>3.76</td>
                            <td>13</td>
                            <td>68</td>
                            <td>419</td>
                        </tr>
                        <tr class="deckTableRow addCard">
                            <td>War</td>
                            <td>l33t</td>
                            <td>1.33</td>
                            <td>20</td>
                            <td>5</td>
                            <td>234</td>
                        </tr>
                        <tr class="deckTableRow addCard">
                            <td>BestGame</td>
                            <td>best_player</td>
                            <td>2.3</td>
                            <td>36</td>
                            <td>9</td>
                            <td>514</td>
                        </tr>
                        <tr class="deckTableRow addCard">
                            <td>Aaaaa</td>
                            <td>Bbbbb</td>
                            <td>3.06</td>
                            <td>13</td>
                            <td>70</td>
                            <td>323</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>