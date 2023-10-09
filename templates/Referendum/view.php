<script src="/js/identities.js"></script>
<?php 
    //Relevant data for display on referendum page
    $address = $referendum->submission_deposit_address;
    $total_votes = $referendum->tally_ayes + $referendum->tally_nays;
    $percentage_aye = number_format((float)calculateVote($total_votes, $referendum->tally_ayes), 2, '.', '');
    $percentage_nay = number_format((float)calculatevote($total_votes, $referendum->tally_nays), 2, '.', '');
    $percentage_support = calculateVote($total_votes, $referendum->tally_support);
    $voting_period = 28 * 86400;
    $finalized = "Deciding"; 

    if($referendum->final > 0){
        if($referendum->tally_ayes > $referendum->tally_nays){
            $finalized = "Approved";
        }
        else{
            $finalized = "Rejected";
        }
    }
?>
<div>
    <input type="hidden" id="referendum-id" value="<?= h($referendum->id) ?>">
    <div class="columns" style="margin-top: 2vh">
        <div class="column is-offset-3">
            <div class="flex row">
                <a class="referendum-link" href="/referenda"><h3> Referenda &nbsp; </h3></a>
                <h3>/ <?= $network ?> Referendum <?= $referendum->referenda_number ?></h3>
            </div>
        </div>
    </div>

    <!-- referenda content -->
    <div class="columns referendum-container">
        <div class="container column is-offset-3 is-5">
            <div class="is-full-width flex row margin-top" >
                <div class="flex row align-center">

                    <!-- category -->
                    <p id="category" class="margin-right"> 
                        <?php if (!empty($user->role_id) && $user->role_id == 2) { ?>
                            <select id="category-dropdown"/>
                                <?php
                                    //Iterate through all categories and display the correct one 
                                    foreach ($classifications as $id => $classification_name): 
                                ?>
                                    <option value="<?= $id ?>" name="<?= $classification_name ?>" <?= $classification == $classification_name ? 'selected' : '' ?>>
                                        <?= h($classification_name) ?>
                                    </option>
                                <?php 
                                    endforeach; 
                                ?>
                            </select>

                            <div id="success-icon" style="display: none;">
                                <img src="/img/success-35-16.png" alt="Success">
                            </div>

                        <?php 
                            } 
                            else 
                            { 
                        ?>
                            <?= $classification ?> 
                    </p>
                    <?php 
                        } 
                    ?>
                </div>
            </div>
           
            <h1 class="margin-top">
                <?php 
                    if (!empty($referendum->title)) { 
                        echo $referendum->title; 
                    } else { 
                        echo "Untitled Referendum"; 
                    } 
                ?>
                </h1>

            <!-- name and date tags -->
            <div class="flex col margin-top">
                <div class="flex row align-center">

                    <!-- identity of proposer -->
                    <div class="flex row align-center half-width table-details">
                        <div class="flex row ">
                            <div class="identicon"></div>
                            <p class="proponent-address tooltip" data-address="<?= $address ?>" data-network="<?= $network ?>">
                                <?php
                                $address_prefix = substr($address, 0, 4);
                                $address_suffix = substr($address, strlen($address) - 4, strlen($address));
                                $address = $address_prefix.'...'.$address_suffix;
                                echo($address); 
                                ?>
                            </p>
                            <div class="container floating-container flex col" style="padding: 0rem;">
                                <!-- Twitter -->
                                <div class="identity-social" id="twitter">
                                    <h4>Twitter &nbsp;</h4>
                                    <p> </p>
                                </div>
                                
                                <!-- Riot -->
                                <div class="identity-social" id="riot">
                                    <h4>Riot &nbsp;</h4>
                                    <p></p>
                                </div>
                              
                                <!-- email -->
                                <div class="identity-social" id="email">
                                    <h4>Email &nbsp;</h4>
                                    <p> </p>
                                </div>
                               
                                <!-- website -->
                                <div class="identity-social" id="website">
                                    <h4>Website &nbsp;</h4>
                                    <p> </p>
                                </div>
                            </div> 
                        </div>
                    
                        <!-- Date submitted proposal -->
                        <p><?= date("F d, Y", $referendum->submission_ts) ?></p>

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

                    <!-- Proposal URL to Subsquare and Polkassembly -->
                    <div class="flex row right-align">
                        <a href="https://<?= $network ?>.subsquare.io/referenda/<?= $referendum->referenda_number ?>" target="_blank" class="icon Subsquare"></a>
                        <a href="https://<?= $network ?>.polkassembly.io/referenda/<?= $referendum->referenda_number ?>" target="_blank" class="icon Polkassembly"></a>
                    </div>
                </div>
            </div>
            <div class="line"></div>

            <!-- container for proposal content -->
            <div class="markdown-content">
                <?= $referendum->description ?>
            </div>
        </div>
        
        <!-- proposal sidebar -->
        <div id="sidebar" class="column is-2 " >
            <div class="container margin-bottom margin-top" style="height: 80%;">
                <!-- container for time left to vote-->
                <div class="is-full-width margin-top">
                    <div class="flex row" style="width: 100%;">
                        <h2>status</h2>
                        <?php 
                            if($referendum->final > 0){
                        ?>
                        <div class="right-align" style="margin-top: 0.5rem;">
                            <p class="<?= $finalized ?>" style="opacity: 75%;">&nbsp;<?= $finalized ?></p>
                        </div>
                        <?php
                        }
                        ?> 
                    </div>

                    <!-- bar container -->
                    <div class="margin-top bar-container">
                        <div class="full-width flex row">
                            <?php if($referendum->final) { ?>
                                <p>Finalized on </p>
                                <p class="days-left right-align">
                                    <?= date("F d, Y", $referendum->confirmed_ts) ?>
                                </p>
                            <?php
                                } 
                                else 
                                {
                            ?>
                            <p>Decision Period</p>
                            <p class="days-left right-align">
                                <?= date("F d, Y", ($referendum->submission_ts + $voting_period))  ?>
                            </p>
                            <?php 
                                 } 
                            ?>
                        </div>

                        <div class="vote-bar">
                            <div class="percentage-bar time" style="width: <?= $percentage_aye ?>%;"></div>
                        </div>
                    </div>

                    <div class="line"></div>

                    <div class="margin-top">
                        <div class="full-width flex row">
                            <h4>Request </h4>
                            <div class="right-align flex col">
                                <p class="right-align"><?= $referendum->amount ?>&nbsp; <?= $network_short ?></p>
                                <p class="label right-align" style="opacity: 0.5;">$<?= $referendum->submission_exchange_rate * $referendum->amount ?> USD </p>
                            </div>
                        </div>

                        <div class="full-width flex row margin-top">
                            <h4>Beneficiary</h4>
                            <div class="flex row align-center right-align">
                                <div class="identicon"></div>
                                <p>
                                    <?php 
                                        echo($address); 
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="full-width flex row margin-top">
                            <h4>Referendum #</h4>
                            <p class="right-align"><?= $referendum->referenda_number ?></p>
                        </div>
                        
                        <?php 
                            if($referendum->final){
                                if($referendum->tally_ayes > $referendum->tally_nays && $referendum->confirming_since === 0 ){
                                ?>
                                <br/>
                                <div class="full-width flex row">
                                    <h4>Received Amount</h4>
                                    <p class="right-align">$<?= $referendum->amount * $referendum->executed_exchange_rate ?> USD</p>
                                </div>
                                <br/>
                                <div class="full-width flex row">
                                    <h4>Difference</h4>
                                    <p class="right-align">$<?= $referendum->amount * ($referendum->executed_exchange_rate - $referendum->submission_exchange_rate) ?> USD</p>

                                </div>
                                <?php
                                }
                            }
                        ?>

                    </div>
                </div>
                
                <div class="line margin-top"></div>

                <div class="flex col full-width">
                    <div class="flex row align-center">
                        <h4>Reimbursement</h4>
                        <p class="right-align"> No </p>
                    </div>
                    
                    <div class="flex row align-center margin-top">
                        <h4>Payment Schedule </h4>
                        <p class="right-align"> YYYY/MM/DD </p>
                    </div>
                 
                    <div class="flex row align-center margin-top">
                        <h4>Track </h4>
                        <p class="right-align"> <?= $referendum->track ?> </p>
                    </div>
                </div>
                
                <div class="line margin-top"></div>
                
                <!-- voting stats -->
                <div class="full-width margin-bottom">
                    <div class="flex row full-width align-center">
                        <h2>votes</h2>
                        <p class="right-align label" style="opacity: 0.5">Controversy Index: 1</p>
                    </div>

                    <div class="margin-top bar-container">
                        <div class="flex row align-center">
                            <div class="icon aye"></div>

                            <div class="flex row full-width">
                                <p class="nay "><?= $referendum->tally_ayes ?> <?= $network_short ?></p>
                                <p class="right-align"><?= $percentage_aye ?>%</p> 
                            </div>
                        </div>

                        <div class="vote-bar">
                            <div class="percentage-bar aye" style="width: <?= $percentage_aye ?>%;"></div>
                        </div>

                      
                        <div class="flex row align-center margin-top">
                            <div class="icon nay"></div>

                            <div class="flex row full-width">
                                <p class="nay "><?= $referendum->tally_nays ?> <?= $network_short ?></p>
                                <p class="right-align"><?= $percentage_nay ?>%</p> 
                            </div>
                        </div>
                        
                        <div class="vote-bar">
                            <div class="percentage-bar nay" style="width: <?= $percentage_nay ?>%;"></div>
                        </div>

                        <p onClick="openModal(voteModal);" class="label clickable margin-top">See Voters</p>

                    </div>
                </div>
              
                <!-- voting buttons -->
                <div class="flex row margin-top">
                    <?php
                        //If referenda is finalized, display whether it has passed or failed. Otherwise, allow voting
                        if($referendum->final > 0){
                            if($referendum->tally_ayes > $referendum->tally_nays){
                            ?>
                            <h2 class="result passed flex align-center justify-center">Passed</h2>
                            <?php
                            }
                            else{
                            ?>
                            <h2 class="result failed flex align-center justify-center">Failed</h2>
                            <?php
                            }
                        }
                        else{
                        ?>
                        <button class="margin-right aye flex align-center"><div class="icon vote-aye"></div>Vote Aye</button>
                        <button class="nay flex align-center"> <div class="icon vote-nay"></div> Vote Nay </button>
                        <?php 
                            } 
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- modal that shows all voters, relevant voter data and delegations -->
<div id="voteModal" class="modal align-center" style="display: none; justify-content: start;">
    <div class="modal-content flex col ">
        <span class="close" onClick="closeModal(voteModal);">&times;</span> 

        <div class="modal-options flex col ">
            <div class="flex">
                <h2 onClick="showAyes(this);"> Ayes </h2>
                <h2 onClick="showNays(this);"> &nbsp;Nays </h2>
            </div>

            <div class="wallets-container flex col full-width">
                <div id="votersList" class="flex" data-index="<?= $referendum->referenda_number ?>" data-network="<?= $network ?>" data-decimal-places="1000000000000">
                    <ul id="aye_list" class="full-width" data-network-short="<?= $network_short ?>" >
                    </ul>
                    <ul id="nay_list" class="full-width" style="display: none;"></ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- modal that shows delegations -->
<div id="delegateModal" class="modal align-center" style="display: none; justify-content: start;">
    <div class="modal-content flex col ">
        <span class="close" onClick="closeModal(delegateModal);">&times;</span>
            
        <div class="modal-options flex col ">
            <div class="flex">
                <h2> Delegates </h2>
            </div>

            <div class="wallets-container flex col full-width">
                <ul id="delegatesList" class="flex col">
                </ul>
            </div>
        </div>

    </div>
</div>

<script>
    //Animates vote icons
    const parent_nay = document.querySelector('.icon.vote-nay');
    const parent_aye = document.querySelector('.icon.vote-aye');
    const aye_container = document.querySelector('button.aye');
    const nay_container = document.querySelector('button.nay');

    animInit(aye_container, parent_aye, 'aye-lottie.json');
    animInit(nay_container, parent_nay, 'nay-lottie.json');
</script>

<?php
    //Converts votes to percentages
    function calculateVote($total, $votes){
        if($total <= 0){
            return(0);
        }
        $percentage = ($votes / $total) * 100;
        return($percentage);
    }
?>


<script>
    //Function that shows identities
    function identitySocial(data, id){
        var object = document.getElementById(id);
        var pTag = document.querySelector("#" + id + " > p");
 
        if(data ){
            pTag.innerHTML = data;
        }
        else{
            object.style.display = "none";
        }
    }

    const proponent = document.querySelector('.proponent-address');
    var name, email, website, twitter, riot;
    var address = proponent.dataset.address;
    var network = (proponent.dataset.network === "Polkadot") ?  'wss://rpc.polkadot.io' :  'wss://kusama-rpc.polkadot.io';
    const resultPromise = identity.getIdentity(network, address);
        resultPromise.then(result => {
        try{
            email   = result.email;
            website = result.website;
            twitter = result.twitter;
            riot    = result.riot;
            name    = result.name;
            
            proponent.innerHTML = result.name;
            identitySocial(email,   'email'  );
            identitySocial(twitter, 'twitter');
            identitySocial(riot,    'riot'   );
            identitySocial(website, 'website');
            
        }
        catch(error){
            identitySocial(email,   'email'  );
            identitySocial(twitter, 'twitter');
            identitySocial(riot,    'riot'   );
            identitySocial(website, 'website');
        }
    });
</script>


<script>
    //Fetches voter data
    let loading;
    let delegateList = [];
    let count;
    let pageCount = 0;
    let delegatesSet = false;

    const decimal_places = 1000000000000;
    const modal = document.getElementById('voteModal');
    const ayeList = document.getElementById('aye_list');
    const nayList = document.getElementById('nay_list');

    function setCount(value) {
        count = value;
    }

    //Pushes voters to array
    function setVotesList(value) {
        delegateList.push(value);
    }

    //Shortens address
    function shortenAddress(address){
        var prefix = address.substr(0, 4);
        var suffix = address.substr(address.length - 4, 4);
        return(prefix + '...' + suffix);
    }
    
    //Populates list with voter information
    function setSubscanVotersList(value) {
        const shortname = ayeList.getAttribute('data-network-short');
        for(var i = 0 ; i < value.length; i++){
            
            if(value[i].delegate_account !== null){
                setVotesList(value[i]);
            }
            else{
                var address = shortenAddress(value[i].account.address);;
                if(value[i].account.hasOwnProperty('display')){
                    address = value[i].account.display;
                }
                
                const conviction = value[i].conviction;
                const amount = ((parseFloat(value[i].amount)) / decimal_places) * parseFloat(conviction) ;
                var row = `
                    <div id="${value[i].account.address}" class="category-chart" onclick="(() => {openModal(delegateModal); populateDelegateModal(${value[i].account.address}); })() ">
                        <div class="flex">
                            <p class="label" style="opacity: 50%;"> <b> conviction: ${conviction}x </b> &nbsp; </p>
                            <p class="label"> delegations: &nbsp; </p>
                            <p class="delegation-count label">${0} </p>
                        </div>
                
                        <div class="flex row align-center">
                            <div class="icon identicon"></div>
                            <h4> ${address}</h4>
                            
                            <p class="label right-align"> <span class="amount">${amount}</span> ${shortname}</p>
                        </div>
                    </div>`;

                if(value[i].status === 'Ayes'){
                    ayeList.innerHTML += row;
                }
                else{
                    nayList.innerHTML += row;
                }
            }
        }
    }

    //Fetches voter data from SubscanAPI
    function fetchVotersListSubscan(index, chain_network) {
    
        fetch(`https://${chain_network}.api.subscan.io/api/scan/referenda/votes`, {
            body: JSON.stringify({
                page: pageCount,
                referendum_index: index,
                row: 100
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-API-Key': '939e5b4e9d64472c859fdcf32768875e'
            },
            method: 'POST'
        }).then(async (res) => {
            const votersData = await res.json();
            if (votersData && votersData.data && votersData.data.list) {
                if (!count) {
                    setCount(votersData.data.count);
                }
                
                setSubscanVotersList(votersData.data.list);

                if(pageCount < count/100){
                    pageCount++;
                    fetchVotersListSubscan(index, chain_network);
                }

            }
        }).catch((err) => {
            console.error('Error in fetching voters data:', err);
        }).finally(() => {
            if((pageCount < 1 && count/100 < 1 || pageCount > count/100) && !delegatesSet){
                setDelegationCount(delegateList);
                delegatesSet = true;
            }
        });
    }

    const container = document.getElementById('votersList');
    const index = parseFloat(container.getAttribute('data-index'));
    const chain_network = container.getAttribute('data-network');

    fetchVotersListSubscan(index, chain_network);

    //Sets delegation count for each voter
    function setDelegationCount(dl){
        for(var i = 0; i < dl.length; i++){
            const address = dl[i].delegate_account.address;
            const parent = document.querySelector(`#${address} > div > .delegation-count`);
            const amount = document.querySelector(`#${address} > div > p > .amount`);
            var count = parseFloat(parent.innerHTML) + 1;

            const dl_amount = ((parseFloat(dl[i].amount)) / decimal_places) * parseFloat(dl[i].conviction); 
            amount.innerHTML = parseFloat(amount.innerHTML) + dl_amount;
            parent.innerHTML = count;
        }
    }

    //Populates delegation list
    function populateDelegateModal(obj){
        const dl_list = document.getElementById('delegatesList');
        dl_list.innerHTML = "";
       
        for(var i = 0; i < delegateList.length; i++){
            const address = shortenAddress(delegateList[i].account.address);
     
            if(obj.id === delegateList[i].delegate_account.address){
                const amount = delegateList[i].conviction * (parseFloat(delegateList[i].amount)/decimal_places);
                var row = `
                    <div class="category-chart" >
                        <div class="flex">
                            <p class="label" style="opacity: 50%;"> <b> conviction: ${delegateList[i].conviction}x </b> &nbsp; </p>

                        </div>
                
                        <div class="flex row align-center">
                            <div class="icon identicon"></div>
                            <h4> ${address}</h4>
                            
                            <p class="label right-align"> <span class="amount">${amount}</span> </p>
                        </div>
                    </div>`;
                dl_list.innerHTML += row;
            }
        }
    }

    //Opens voter modal
    function openModal(obj){
        obj.style.display = 'flex';
    }

    //Closes voter modal
    function closeModal(obj){
        obj.style.display = 'none';
    }

    function showAyes(value){
        nayList.style.display = 'none'
        ayeList.style.display = 'block';
    }

    function showNays(value){
        ayeList.style.display = 'none'
        nayList.style.display = 'block';
    }

</script>

