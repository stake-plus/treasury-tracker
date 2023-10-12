<script src="./js/identities.js"></script>
<script src="/js/sidebar.js"></script>

<?php 
    //Template function for sidebar selections
    function checkbox($classification, $form_id, $key, $listClassifications){
        ?>
        <li onClick="checkBox(this, <?= $form_id ?>)" class="checkbox-list category <?= $classification ?> checked flex row align-center">
            <div class="checkbox">
                <div class="check"></div>
            </div>
            <p><?= $classification ?></p>
        </li>
        <li style="display: none">
            <input class="checkbox-list-hidden category" id="<?= $form_id ?>" type="checkbox" name="classifications[]" id="classification_<?= $key ?>" class="classificationCheckbox" value="<?= $key ?>" checked>
            <label for="classification_<?= $key ?>"><?= $classification ?></label>
        </li>
        <?php 
    }
?>

<div class="columns">
    <!-- sidebar -->
    <div id="sidebar" class="slide-in" onload="loadIn();">
        <!-- content container -->
        <div class="justify-center align-center" style="padding-top: 6rem;">
            <div class="flex align-center">
                <h1>Referenda</h1>
            </div>
            
            <form class="margin-top" method="get" action="">
                <!-- sort by -->
                <div class="flex col">
                    <div class="margin-top">
                        <h4>Sort By</h4>
                        <ul>
                            <div class="flex row space-around">
                                <li class="sidebar-item" onClick=""><?= $this->Paginator->sort('Referenda.submission_ts', 'Newest', ['direction' => 'desc', 'lock' => true]) ?></li>
                                <li class="sidebar-item" onClick=""><?= $this->Paginator->sort('Referenda.submission_ts', 'Oldest', ['direction' => 'asc', 'lock' => true]) ?></li>
                            </div>
                        </ul>
                    </div>
                </div>

                <?php 
                    if (empty($this->request->getQuery('sort'))) 
                    {
                        $sort = "Referenda.submission_ts";
                    } 
                    else 
                    {
                        $sort = $this->request->getQuery('sort');
                    }
                ?>

                <input type="hidden" name="sort" value="<?= $sort ?>">
                    <?php 
                        //Checks whether to sort by Newest or Oldest
                        if (empty($this->request->getQuery('direction'))) {
                            $direction = "desc";
                        } else {
                            $direction = $this->request->getQuery('direction');
                        }
                    ?>
                <input type="hidden" name="direction" value="<?= $direction ?>">
                
                <!-- filter by -->
                <div class="margin-top">
                    <h4 for="filter-by">Filter by</h4>
                    <?php
                        //Checks whether to filter by all, deciding or finalized. Defaults to All.
                        $defaultState = "0";
                        $state = $this->request->getQuery('state', $defaultState);
                    ?>
                    
                    <select name="state" class="dropdown-container">
                        <option value="0" <?= $state == '0' ? 'selected' : '' ?>>All Referenda</option>
                        <option value="1" <?= $state == '1' ? 'selected' : '' ?>>Deciding Referenda</option>
                        <option value="2" <?= $state == '2' ? 'selected' : '' ?>>Finalized Referenda</option>
                    </select>
                    
                </div>

                <div class="line"></div>

                <!-- categories -->
                <div class="margin-top">
                    <div class="flex row align-center">
                        <h4>Categories</h4>
                        <div class="right-align flex row justify-center">
                            <p onClick="selectAll()"class="selector label">Select All </p>
                            <p onClick="deselectAll()"class="selector label">&nbsp; Deselect All </p>
                        </div>

                    </div>
                    <div id="classificationList" style="display: flex; width: 100%; flex-wrap: wrap;">
                        <?php
                            //Labels each proposal by category					
                            $defaultClassifications = ['0','1','2','3','4','5','6','7','8','9'];
                            $listClassifications = $this->request->getQuery('classifications', $defaultClassifications);

                            ksort($classifications);
                            
                            foreach ($classifications as $key => $classification):
                                $classification_box = explode(' ', $classification);
                        ?>
                        
                        <li onClick="checkBox(this, <?= $classification_box[0] ?>)" class="checkbox-list category <?= $classification ?> checked flex row align-center">
                            <div class="checkbox">
                                <div class="check flex center">
                                    <div class="icon category <?= $classification ?>" style="margin-right: 0"></div>
                                </div>
                            </div>
                            <p><?= $classification ?> </p>
                        </li>

                        <!-- hidden fields -->
                        <li style="display: none">
                            <input class="checkbox-list-hidden category" 
                                   id="<?= $classification_box[0] ?>" 
                                   type="checkbox" name="classifications[]" 
                                   id="classification_<?= $key ?>" class="classificationCheckbox" value="<?= $key ?>" 
                                   <?= in_array($key, $listClassifications) ? 'checked' : '' ?>
                            >
                            <label for="classification_<?= $key ?>"><?= $classification ?></label>
                        </li>
                        <?php
                            endforeach;
                        ?>
                    </div>
                </div>
                <div class="line"></div>
                <!-- networks -->
                <div class="margin-top">
                    <div class="flex row align-center">
                        <h4 for="filter-by">Networks</h4>
                        <div class="right-align flex row justify-center">
                            <p onClick="selectAllNetworks()" class="selector label">Select All </p>
                            <p onClick="deselectAllNetworks()" class="selector label">&nbsp; Deselect All </p>
                        </div>
                    </div>
                    <?php
                        $defaultNetworks = ['1','2'];
                        $supportedNetworks = $this->request->getQuery('networks', $defaultNetworks);
                    ?>


                    <ul style="margin-left: 1.5rem;">
                        <?php
                            //Iterates through all proposals for Polkadot and Kusama
                            foreach ($networks as $id=>$network) {
                                if ($network['type'] == "relay") {
                        ?>
                        <li onClick="checkBox(this, <?= $network['long_name'] ?>)" class="checkbox-list network checked flex row align-center">
                            <div class="checkbox">
                                <div class="check"></div>
                            </div>

                            <div class="icon <?= $network['short_name'] ?>"></div>

                            <p><?= $network['long_name'] ?></p>
                        </li>
                        <div style="display: none;">
                            <input class="checkbox-list-hidden network" 
                                   type="checkbox" name="networks[]" 
                                   value="<?= $id ?>" 
                                   id="<?= $network['long_name'] ?>" <?= in_array($id, $supportedNetworks) ? 'checked' : '' ?>
                            >
                        </div>
                        <?php
                                }
                            }
                        ?>

                        <?php
                            //Iterates through all proposals for parachains
                            foreach ($networks as $id=>$network) {
                                if ($network['type'] == "para") {
                        ?>
                        <li onClick="checkBox(this, <?= $network['long_name'] ?>)" class="checkbox-list network checked flex row align-center">
                            <div class="checkbox">
                                <div class="check"></div>
                            </div>
                            <div class="icon <?= $network['short_name'] ?>"></div>
                            <p><?= $network['long_name'] ?></p>
                        </li>
                        <div style="display: none;">
                            <input class="checkbox-list-hidden network" 
                                   type="checkbox" name="networks[]" 
                                   value="<?= $id ?>" 
                                   id="<?= $network['long_name'] ?>" <?= in_array($id, $supportedNetworks) ? 'checked' : '' ?>
                            >
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </ul>
                </div>
            
                <button class="margin-top full-width" type="submit">Filter</button>

            </form>
        </div>
    </div>
    
    <div class="banner-container flex align-center">
            <div>
                <div id="banner-background" class="flex align-center frosted-glass"></div>
            </div>

            <div class="flex col banner-content">
                <p style="color: #f4f4f4;" class="label">Article Feature</p>
                <h1 style="color: #f4f4f4;" class="big"> How Polkadot's Blockspace Empower's Developers </h1>
                <div style="color: #f4f4f4;" class="line" style="border-color: black; opacity: 50%;"></div>

                <div class="flex row space-around" style="width: 75%;">
                    <p style="color: #f4f4f4;"> September 27, 2023 </p>
                    <p style="color: #f4f4f4;"> 5 min read </p>
                    <p style="color: #f4f4f4;"> By Polkadot </p>
                </div>

                <div class="margin-top">
                    <button>Read More</button>
                </div>
            </div>
    </div>
    


    <div class="column is-7 is-offset-1" style="margin-top: 400px;">
        <div class="flex col">
            <div class="flex align-center">
                <h1>Referenda</h1>
                <p class="right-align"> 
                    <b><?= $count["total"] ?> Proposals</b>
                </p>
            </div>

            <div class="line"></div>

        </div>
        <?php
            //Gets all relevant data for each referendum 
            $i = 0;
            foreach ($referenda as $referendum):
                $i++;
                $total_votes =  $referendum->tally_ayes + $referendum->tally_nays;
                $percentage_aye = intval(calculateVote($total_votes, $referendum->tally_ayes), 10);
                $percentage_nay = intval(calculatevote($total_votes, $referendum->tally_nays), 10);
                $finalized = "Deciding"; 
                $address = $referendum->submission_deposit_address;
                $voting_period;
                ($networks[$referendum['network_id']]['long_name'] === 'Kusama') ? ( $voting_period = 14 * 86400) : ($voting_period = 28 * 86400);

                if($referendum->final > 0){
                    if($referendum->tally_ayes > $referendum->tally_nays){
                        $finalized = "Approved";
                    }
                    else{
                        $finalized = "Rejected";
                    }
                }
        ?>
        <!-- proposal table cel -->
        <a class="table-link flex col justify-center" style="position: relative" data-href="/referendum/<?= $networks[$referendum['network_id']]['long_name'] ?>/<?= $referendum->referenda_number ?>">
            <!-- network tab -->
            <div class="flex row align-center justify-center finalized network <?= $networks[$referendum['network_id']]['long_name'] ?>" 
                 style="margin-bottom: 0.5rem; position: absolute; top: -1rem;"
            >   
                <div class="icon mono white <?= $networks[$referendum['network_id']]['long_name'] ?>"></div>
                <p class="label" style="color: #f4f4f4;">
                    <?= $networks[$referendum['network_id']]['long_name'] ?>
                </p>
            </div>

            <div class="flex row">
             <!-- information -->
                <div class="flex col" style="width: 60%;">
                    <div class="flex row align-center">
                        <div style="border-radius: 50%; width: 0.75rem; height: 0.75rem;" class="icon <?php if (isset($classifications[$referendum['w3f_classification']])) { echo $classifications[$referendum['w3f_classification']]; } ?>"></div>
                            <p>
                                <?php
                                    //If there is a category, use it. Otherwise, it is considered uncategorized. 
                                    if (isset($classifications[$referendum['w3f_classification']])) 
                                    { 
                                        echo $classifications[$referendum['w3f_classification']]; 
                                    } 
                                ?>
                            </p>
                        </div>
                    <h1>
                        <?php
                            //If proposal has a title, use it. Otherwise, give it Untitled Proposal 
                            if (!empty($referendum->title)) 
                            { 
                                echo $referendum->title; 
                            } 
                            else 
                            { 
                                echo "Untitled Proposal"; 
                            } 
                        ?>
                    </h1>
                    <div class="margin-top flex row align-center"> 

                        <div class="flex row align-center table-details">
                            <!-- identity of proposer -->
                            <div class="flex row">
                                <div class="identicon"></div>
                                <p id="<?= $networks[$referendum['network_id']]['long_name']?>-<?= $referendum->referenda_number ?>" 
                                    class="proponent-address" 
                                    data-address="<?= $address ?>" 
                                    data-network="<?= $networks[$referendum['network_id']]['long_name']?>
                                ">
                                    <?php 
                                        $address_prefix = substr($address, 0, 4);
                                        $address_suffix = substr($address, strlen($address) - 4, strlen($address));
                                        echo($address_prefix.'...'.$address_suffix); 
                                    ?>
                                </p>
                                <div class="container floating-container flex col" style="margin: 0; padding: 0rem;">
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
                            <p><?= date("F d, Y", $referendum->submission_ts)  ?></p>
		
                            <!-- Icon stats for likes, dislikes, views -->
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
                
                <div class="flex col align-center justify-center" style="position: relative; width: 40%;">

                    <!-- percentage bars -->
                    <div >
                        <div>
                            <div>
                                <div class="flex row align-center">
                                    <div class="icon aye"></div>
                                    <p><?= $referendum->tally_ayes ?> <?= $networks[$referendum['network_id']]['short_name'] ?></p>
                                    <p class="right-align"><?= $percentage_aye ?>%</p>
                                </div>
                                <div class="vote-bar" style="width: 200px">
                                    <div>
                                        <div class="percentage-bar aye" style="width: <?= $percentage_aye ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="margin-top" >
                            <div class="flex row align-center">
                                <div class="icon nay"></div>
                                <p><?= $referendum->tally_nays ?> <?= $networks[$referendum['network_id']]['short_name'] ?></p>
                                <p class="right-align"><?= $percentage_nay ?>%</p>
                            </div>

                            <div class="vote-bar" style="width: 200px;">
                                <div>
                                    <div class="percentage-bar nay" style="width: <?= $percentage_nay ?>%;"></div>
                                </div>
                            </div>
                        </div>

                        <div style="width: 200px; margin-top: 0.5rem;">
                            <div class="flex row align-center" style="position: absolute; width: 200px">
                                <p class="<?= $finalized ?>" style="opacity: 75%;">
                                    &nbsp;<?= $finalized ?>
                                </p>
                                <?php
                                    //If proposal has not finalized, then show time left 
                                    if($finalized === 'Deciding'){
                                ?>
                                    <p class="days-left right-align">
                                        <?= date("F d, Y", ($referendum->submission_ts))  ?>
                                    </p>
                                <?php
                                    } 
                                ?>
                            </div>
                        </div>

                    </div>
                    
                </div>

            </div>
        
        </a>

        <?php endforeach; ?>
        <div class="columns" style="margin-bottom: 0;">
            <div class="column is-6 is-offset-3 flex row ">
                <div class="paginate-container flex row right-align" >
                    <?= $this->Paginator->prev('<') ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next('>') ?>
                </div>
            </div>
        </div>
    </div>
    

<?php
    //Converts total votes to percentages
    function calculateVote($total, $votes){
        if($total <= 0){
            return(0);
        }
        $percentage = ($votes / $total) * 100;
        return($percentage);
    }
?>


<script type="text/javascript">

    loadIn();
    sidebarInit();
  
</script>


<script type="text/javascript">
    //Check for identities
    function identitySocial(data, id, object_id){
        var object = document.querySelector("#" + object_id + " + div > " + "#" + id);
        var pTag = document.querySelector("#" + object_id + " + div > " + "#" + id + " > p");
     
        if(data ){
            console.log(data);
            pTag.innerHTML = data;
        }
        else{
            object.style.display = "none";
        }
    }

    const proponent = document.querySelectorAll('.proponent-address');
    var objects = []; 
    for(var i = 0; i < proponent.length; i++){
        (function (i) { 
            var name, email, website, twitter, riot;
            const id = proponent[i].id;
            const address = proponent[i].dataset.address;
            const network = (proponent[i].dataset.network === "Polkadot") ?  'wss://rpc.polkadot.io' :  'wss://kusama-rpc.polkadot.io';
            const resultPromise = identity.getIdentity(network, address);

            resultPromise.then(result => {
                try{
                    email = result.email;
                    website = result.website;
                    twitter = result.twitter;
                    riot = result.riot;
                    name = result.name;
                    
                    proponent[i].innerHTML = result.name;

                    identitySocial(email, 'email', id);
                    identitySocial(twitter, 'twitter', id);
                    identitySocial(riot, 'riot', id);
                    identitySocial(website, 'website', id);
                }
                catch(error){
                    identitySocial(email, 'email', id);
                    identitySocial(twitter, 'twitter', id);
                    identitySocial(riot, 'riot', id);
                    identitySocial(website, 'website', id);
                }
            });
        })(i);
    }


</script>
