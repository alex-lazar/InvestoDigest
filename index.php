<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investodigest</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
</head>
<body>
    <?php 
    // get stock data from json file into an array
    $stock_data = file_get_contents("stock_data.json"); 
    $stock_data = json_decode($stock_data, true);
    ?>
    <section class="hero is-info">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Intelligent Stock Curation
                </h1>
                <p class="subtitle">
                    Updated as of <?php echo($stock_data['date']) ?>
                </p>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <div class="content">
                        <p class="subtitle">How does it work?</p>
                        <p>
                            A cron job collects data from Yahoo Finance. 
                            Then finds companies that have 
                            been profitable long-term & have a P/E of less than 20.
                        </p>
                    </div>
                </div>
                <div class="column">
                    <div class="content">
                        <p class="subtitle">Is it free?</p>
                        <p>
                            Yes, 100% free forever. This website makes money through 
                            affiliate marketing & donations, both of which you can find under 
                            the stocks table.
                        </p>
                    </div>
                </div>
                <div class="column">
                    <div class="content">
                        <p class="subtitle">Disclaimer</p>
                        <p>
                            This website doesn't claim to give you a foolproof investment 
                            strategy. You can't outsource your thinking & 
                            research. 
                        </p>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-7">
                    <div class="content">
                        <p>
                            *The cron job analyzes stocks from a wide range of 
                            exchanges: Hong Kong, Singapore, USA, Toronto etc. You can use 
                            the search form to look specifically for one exchange.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <table id="myTable" class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>Ticker</th>
                        <th>Long Name</th>
                        <th>Price</th>
                        <th>Exchange</th>
                        <th>Avg. EPS (Past 4 Years)</th>
                        <th>Trailing EPS</th>
                        <th>Trailing P/E</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Ticker</th>
                        <th>Long Name</th>
                        <th>Price</th>
                        <th>Exchange</th>
                        <th>Avg. EPS (Past 4 Years)</th>
                        <th>Trailing EPS</th>
                        <th>Trailing P/E</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    // loops over data in the json file & creates table rows
                    foreach ($stock_data['good_stocks'] as $key => $value) { ?>
                        <tr>
                            <th><?php echo($value["Ticker"]); ?></th>
                            <td><?php echo($value["LongName"]); ?></td>
                            <td><?php echo($value["Price"]." ".$value['Currency']); ?></td>
                            <td><?php echo($value["Exchange"]); ?></td>
                            <td><?php echo($value["AvgEPS"]." ".$value['Currency']); ?></td>
                            <td><?php echo($value["TrailingEPS"]." ".$value['Currency']); ?></td>
                            <td><?php echo($value["TrailingPE"]); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <div class="box">
                        <div class="content">
                            <p>
                                <strong>
                                    <a href="https://paypal.me/alexlazar97?locale.x=en_US">
                                        Donate
                                    </a>
                                </strong>
                            </p>
                            <p>
                                If you find this website useful, donate.
                            </p>
                        </div>
                    </div>
                    <div class="box">
                        <div class="content">
                            <p>
                                <strong>
                                    <a href="https://hypefury.com/?via=mauricedesaxe91">
                                        Hypefury
                                    </a>
                                </strong>
                            </p>
                            <p>
                                Hypefury is a SaaS that helps with 
                                scheduling tweets (among other features).
                            </p>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="box">
                        <div class="content">
                            <p>
                                <strong>
                                    <a href="http://abuamerican.com">
                                        Abuamerican
                                    </a>
                                </strong>
                            </p>
                            <p>
                                Abuamerican coaches Muslims on how to get
                                their marriages right.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                Creator: <a href="https://alexlazar.dev">Alex Lazar</a>
            </p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
    </script>
    <script async src="https://cdn.splitbee.io/sb.js"></script>
</body>
</html>