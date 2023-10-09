<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
    $categories = ['Infrastructure', 'Network Security', 'Ecosystem', 'Marketing', 'Community', 'Software', 'Bounties', 'Liquidity Provisions', 'Others'];

    //Function that outputs a table-link for Latest Proposals
    function chartCategories($category){
        ?>
        <div class="category-chart">
            <div class="flex row">
                <p class="label"> 12.5% </p>
                
            </div>
       
            <div class="flex row align-center">
                <div class="icon-color <?= $category ?>" style="border-radius: 2rem; width: 0.75rem; height: 0.75rem;"></div>
                <h4> <?= $category ?></h4>
                <p class="right-align label"> 2.5M DOT </p>
            </div>

        </div>
        <?php
    }

    //Function that shortens the address
    function shortenAddress($referendum){
        $address = $referendum->decision_deposit_address;
        $address_prefix = substr($address, 0, 4);
        $address_suffix = substr($address, strlen($address) - 4, strlen($address));
        $short_address = $address_prefix.'...'.$address_suffix;
        return($short_address);
    }

    //Funmction that shows the latest proposals
    function latestProposals($referendum){
       $title = $referendum->title;

       //Currently not imported, network and category are hardcoded
       $network = 'Polkadot';
       $category = 'Infrastructure';
       $short_address = shortenAddress($referendum);
       $date = date("F d, Y", $referendum->submission_ts);
?>
<a >
    <div class="table-link mini">
        <div class="flex col">
            <div class="flex row align-center">
                <div class="icon Polkadot"></div>
                <p><?= $category ?></p>
            </div>
          
            <h4 class="margin-top">
                <?= $title ?>
            </h4>
       
            <div class="flex row space-around margin-top full-width">
                <div class="flex row">
                    <div class="identicon"></div>
                    <p class="label"><?= $short_address ?></p>
                </div>

                <p class="label"><?= $date ?></p>

                <!-- icon stats for likes, dislikes, views -->
                <div class="flex row align-center">
                    <div class="icon-container stats flex row align-center">
                        <div class="icon views"></div>
                        <p class="label">0</p>
                    </div>
                        
                    <div class="icon-container stats flex row align-center">
                        <div class="icon like"></div>
                        <p class="label">0</p>
                    </div>
                    
                    <div class="icon-container stats flex row align-center">
                        <div class="icon dislike"></div>
                        <p class="label">0</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</a>
<?php
    }
?>
<div class="overview-container  flex col">
  
    <div class="column is-8 is-offset-2  " style="position: relative;">
        <div>
            <h1 class="margin-bottom head" > Treasury Tracker Overview </h1>
        </div>

        <div class="line" style="margin: 0;"></div>
    </div>

    <div class="flex row">

        <!-- basic pi chart -->
        <div class="column container flex row is-5 is-offset-2" style="height: fit-content;" >
                <div class="flex col ">
                    <div class="flex col">
                        <h1>Total Spent: Polkadot Treasury</h1>
                        <div class="dropdown-container" style="width: 4rem;">
                            <p> All </p>
                        </div>
                    </div>

                    <!-- chart container-->
                    <div class="flex col " style="position: relative; width: 400px; height: 400px;">
                        <div class="absolute-center">
                            <h1 style="font-size: 2rem;"> 19.59M DOT </h1>
                            <p> Total Amount </p>
                        </div>
                        <canvas id="overview-chart"></canvas>
                    </div>

                </div>

                <!-- select categories -->
                <div class="flex col right-align" style="width: 35%;">
                    <?php
                        //Iterates through all categories and displays them
                        for($i = 0; $i < count($categories); $i++){
                            chartCategories($categories[$i]);
                        }
                    ?>
                </div>
        </div>

         
        <!-- Latest Proposals -->
        <div class="column container is-3" style="margin-left: 1rem">
            <div class="flex col justify-center">
                <h3 class="margin-top">Latest Proposals </h3>
                <div class="line"></div>
            </div>
            <?php
                //Iterates through the latest 5 proposals and displays them
                for($i = 0; $i < 5; $i++){
                    latestProposals($referenda[$i]);
                }
            ?>
            
            <div class="flex align-center justify-center margin-top">
                <a class="full-width"  href="/referenda">
                    <button class="full-width" type="submit">View All</button>
                </a>
            </div>
        </div>

    </div>

</div>

<script type="text/javascript">
    // Sample data for the pie chart
    const categoryColors = ['#F3ACC1','#F1638D', '#D9E693', '#F27532','#635FEC','#F23252', '#F9D793', '#7BD2CA', '#F5B98B'  ];
    var data = {
        datasets: [{
            data: [12, 19, 3, 5, 2, 12, 19, 3, 5, 2, 6, 10 ],
            backgroundColor: categoryColors,
            borderWidth: 0,
        }],
    
    };

    // Get the canvas element
    var ctx = document.getElementById('overview-chart').getContext('2d');

    // Create the pie chart
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins:{

            }
        },
    });
</script>