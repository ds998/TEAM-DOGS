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
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <table id="deckTable" class="table table-hover table-dark table-striped">
                    
                    <thead>
                        <tr class="deckTableRowHeader topRow">
                            <th scope="col">Name</th>
                            <th scope="col">Rating</th>
                            <th scope="col"># of Ratings</th>
                            <th scope="col"># Plays</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($decks as $deck) {
                                echo "
                                <tr class='deckTableRow addCard'> 
                                    <td>{$deck->name}</td>
                                    <td>{$deck->Rating}</td>
                                    <td>{$deck->numberOfRatings}</td>
                                    <td>{$deck->numberOfPlays}</td>
                                </tr>
                                    ";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>