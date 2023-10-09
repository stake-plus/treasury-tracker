<!-- Lottie allows us to add complex icon animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js"></script>

<div class="header">
    <nav class="navbar flex row align-center columns">
        <div class="column flex row is-offset-3 is-6 align-center margin-top">
            <a href="/" class="navbar-brand flex row align-center">
                <img style="width: 30px;margin-right: 0.5rem;"src="/img/TT_Temp_Logo.svg"/>
                <h2>TreasuryTracker</h2>
            </a>
            <div class="right-align flex row align-center">
                <!-- toggle light dark mode -->
                <label class="toggle">
                    <?php
                        //Checks color mode and sets to Light or Dark depending on session
                        $color_mode = $this->request->getSession()->read('mode');
                        if($color_mode === 'dark mode'){
                            ?>
                            <input type="checkbox" checked id="modeToggle">
                            <?php
                        }
                        else{
                    ?>
                    <input type="checkbox" id="modeToggle">
                    <?php 
                        } 
                    ?>
                    <span onClick="toggleMode()" class="slider round"></span>
                </label>

                <!-- Login -->
                <ul class="nav flex row align-center">
                    <?php
                        //If logged in, show the login button as connected. Otherwise display as Login. 
                        if ($loggedIn == true) 
                        { 
                            echo '<li><button class="nav-link" id="connect" data-href="#">Connected</button></li>';
                        } 
                        else 
                        { 
                            echo '<li><button class="nav-link" id="connect" data-href="#">Login</button></li>';
                        } 
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</div>

<nav class="navbar-secondary columns ">
    <ul class="nav column is-6 is-offset-3 flex row align-center">
        <!-- Overview -->
        <li class="nav-link flex row align-center" data-href="/" id="overview">
            <div class="icon search" data-animation="search-lottie.json"></div>
            <p class="flex align-center">Overview</p>
        </li>
        
        <!-- Chains -->
        <li class="nav-link flex row align center" data-href="/chains" id="chains">
            <div class="icon proposals" data-animation="chain-lottie.json"></div> 
            <p class="flex align-center">Chains</p>
        </li>
        
        <!-- Referenda -->
        <li class="nav-link flex row align center" data-href="/referenda" id="referenda">
            <div class="icon proposals" data-animation="proposal-lottie.json"></div> 
            <p class="flex align-center">Referenda</p>
        </li>
        
        <!-- Analytics -->
        <li class="nav-link flex row" data-href="/analytics" id="analytics">
            <div class="icon analytics" data-animation="analytics-lottie.json"></div>
            <p class="parent-float flex align-center">Analytics</p>
        </li>
        
        <!-- Account -->
        <li class="right-align nav-link flex row align center" data-href="/" id="account">
            <div class="icon account" data-animation="account-lottie.json"></div>
            <p class="flex align-center">My Account</p>
        </li>          
    </ul>
</nav>

<?php
    //Output the below extended navbar if we are on the analytics page
    if (strpos($_SERVER['REQUEST_URI'], '/analytics') !== false) {
?>
    <nav class="navbar-secondary columns " style="border-top-style: none;">
        <ul class="nav column is-6 is-offset-3 flex row align-center" style="padding: 0; height: 100%;">
            <li class="nav-link flex row align-center analytics-item" data-href="/analytics/categories" >
                <p class="flex align-center">Spend x Categories</p>
            </li>
                
            <li class="nav-link flex row align-center analytics-item" data-href="/analytics/tracks" >
                <p class="flex align-center">Referenda x Tracks</p>
            </li>
        </ul>
    </nav>
<?php
    }
?>

<!-- Modal that shows when you click Login -->
<div id="myModal" class="modal align-center justify-center">
    <div class="modal-content flex col justify-center">
        <span class="close">&times;</span>
            <?php 
            if ($loggedIn == true) { 
                echo '<input type="hidden" name="loggedin" id="loggedin" value="1">';
                echo '<input type="hidden" name="loggedin" id="user_address" value="'.$user->address.'">';
	          } else { 
                echo '<input type="hidden" name="loggedin" id="loggedin" value="0">';
                echo '<input type="hidden" name="loggedin" id="user_address" value="">';
            } ?>
        <div class="modal-options flex col ">
            <!-- connect -->
            <h2> Select Wallet </h2>
            <div class="wallets-container flex col full-width">
                
                <!-- PJS -->
                <div class="wallet-option">
                    <div onClick="selectWallet()" id="connectJS" type="submit">
                        <div>
                            <div class="flex row align-center margin-bottom">
                                <img class="wallet-logo" src="/img/pjs.png"/>
                                <p class="label right-align">+ Connect </p>
                            </div>
                            
                            <h4>Polkadot JS<h4>
                            <p class="label">polkadot.js.org/extension</p>
                        </div>
                    </div>
                </div>

                <!-- Talisman -->
                <div class="wallet-option disabled">
                    <div>
                        <div>
                            <div class="flex row align-center margin-bottom">
                                <img class="wallet-logo" src="/img/talisman.png"/>
                                <p class="label right-align">+ Coming Soon </p>
                            </div>
                           
                            <h4>Talisman<h4>
                            <p class="label">talisman.xyz</p>
                        </div>
                    </div>
                </div>

                <!-- Nova Wallet -->
                <div class="wallet-option disabled">
                    <div>
                        <div>
                            <div class="flex row align-center margin-bottom">
                                <img class="wallet-logo" src="/img/nova.png"/>
                                <p class="label right-align">+ Coming Soon </p>
                            </div>
                           
                            <h4>Nova Wallet<h4>
                            <p class="label">novawallet.io</p>
                        </div>
                    </div>
                </div>

            </div>

            <div id="wallet-form" class="flex col" style="display: none">
                <?= $this->Form->select('Address', [], ['empty' => true, 'id' => 'address']) ?>
                <?= $this->Form->button('Register', ['id' => 'register']) ?>
                <?= $this->Form->create( null, ['id' => 'registerForm', 'url' => ['controller' => 'Users', 'action' => 'register']]) ?>
                <?= $this->Form->hidden('address', ['id' => 'addressField', 'value' => '']) ?>
                <?= $this->Form->hidden('signature', ['id' => 'signatureField', 'value' => '']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<!-- Temporary popup when site is loaded on mobile as responsiveness is unfinished -->
<div class="mobile col align-center justify-center" style="background-color: var(--light); position: sticky; width: 100vw; height: 110vh; top: 0; left: 0; z-index: 99999; transform: translateY(-100px)">
    <img style="width: 120px" src="/img/TT_Temp_Logo.svg">
    <h1> Mobile coming soon! </h1>
</div>


<script src="/js/headerFunctions.js"></script>