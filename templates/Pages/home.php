<?php
    //needs to be reworked to iterate through DB
    function parachainSummary($name, $ticker, $icon_URL){
        ?>
        <div class="parachain-summary flex row align-center">
            <div class="flex align-center" style="width: 25%;">
                <img class="icon" src="/img/dot.png"/>
                <h2><?= $name ?></h2>
            </div>

            <div class="parachain-data flex col">
                <p class="label">Treasury Total</p>
                <h2>123M <?= $ticker ?></h2>
                <p class="label">$0.01</p>
            </div>

            <div class="parachain-data flex col">
                <p class="label">Treasury Spent</p>
                <h2>0 <?= $ticker ?></h2>
                <p class="label">$0.01</p>
            </div>

            <div class="parachain-data flex col">
                <p class="label">Proposals Approved</p>
                <h2>5 / 10</h2>
                <br/>
            </div>

            <div class="parachain-data flex col">
                <p class="label">Proposals Rejected</p>
                <h2>5 / 10</h2>
                <br/>
            </div>
        </div>
        <?php
    }

?>

<div style="margin-top: 10vh;">

  <!-- Latest Proposal Container -->

    <div class="columns">
        <div class="column is-offset-1 is-10 flex col">
            <div class="inline-flex col">
                <h1 style="padding-right: 20rem;"> Treasury Tracker Overview </h1>
                <div class="line"></div>
            </div>
            <div class="half flex row">
                <div>
                    <h2> Kusama Parachains </h2>
                    <?php parachainSummary("Polkadot", "", "DOT") ?>
                </div>

                <div>
                    <h2> Polkadot Parachains </h2>
                    <?php parachainSummary("Polkadot", "", "DOT") ?>
                </div>
            </div>
        </div>

    </div>

    <div class="columns">
        <div class="column is-offset-3 is-6 ">
            <h2> Overview </h2>
        </div>
    </div>

    <div class="columns is-multiline">
        <div class="temp column is-2 is-offset-3">Treasury Total</div>
        <div class="temp column is-2">Total Funds Spend</div>
        <div class="temp column is-2"></div>
    </div>

    <div class="columns is-multiline">
        <div class="temp column is-2 is-offset-3">Proposals Passed</div>
        <div class="temp column is-2">Total Proposals</div>
        <div class="temp column is-2"> Bounties Collected</div>
    </div>

    <br/>

    <div class="columns">
        <div class="column temp is-6 is-offset-3">
            <div class="flex row">
                <div class="data-viz temp default-height"> Data Visual 1</div>
                <div class="data-viz temp default-height">Data Visual 2</div>
            </div>
        </div>
    </div>
    
</div>
  


