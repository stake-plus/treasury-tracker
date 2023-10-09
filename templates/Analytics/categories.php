<!-- SIDEBAR -->
<div class="columns">
<div id="sidebar" class="column is-2 is-offset-1" class="slide-in" onload="loadIn();" style="padding: 0;" >
        <div class="justify-center align-center" style="padding-top: 6rem;">
			<form method="get" action="">

                <div class="margin-top">
                    <h4 for="filter-by">Time Units</h4>
      		          <?php
    		                $defaultUnits = "month";
				                $units = $this->request->getQuery('units', $defaultUnits);
		                ?>
                        <select id="toggleUnit" name="units" class="dropdown-container">
                            <option value="day" <?= $units == 'day' ? 'selected' : '' ?>>Day</option>
                            <option value="week" <?= $units == 'week' ? 'selected' : '' ?>>Week</option>
                            <option value="month" <?= $units == 'month' ? 'selected' : '' ?>>Month</option>
                            <option value="quarter" <?= $units == 'quarter' ? 'selected' : '' ?>>Quarter</option>
                            <option value="year" <?= $units == 'year' ? 'selected' : '' ?>>Year</option>
                        </select>
                </div>

                <!-- FILTER BY -->
                <div class="margin-top">
                    <h4 for="filter-by">Referenda Status</h4>
                    <?php
                        $defaultState = "1";
                        $state = $this->request->getQuery('state', $defaultState);
                    ?>
                    <select  name="state" class="dropdown-container">
                        <option value="0" <?= $state == '0' ? 'selected' : '' ?>>All Referenda</option>
                        <option value="1" <?= $state == '1' ? 'selected' : '' ?>>Approved Referenda</option>
                        <option value="2" <?= $state == '2' ? 'selected' : '' ?>>Rejected Referenda</option>
                    </select>
                </div>

                <div class="line"></div>

            <!-- CATEGORIES -->
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

                    <!-- HIDDEN FIELDS -->
                    <li style="display: none">
                        <input class="checkbox-list-hidden category" id="<?= $classification_box[0] ?>" type="checkbox" name="classifications[]" id="classification_<?= $key ?>" class="classificationCheckbox" value="<?= $key ?>" <?= in_array($key, $listClassifications) ? 'checked' : '' ?>>
                        <label for="classification_<?= $key ?>"><?= $classification ?></label>
                    </li>
                    <?php
                        endforeach;
                    ?>
                </div>
            </div>
                <div class="line"></div>
                <!-- NETWORKS -->
                <div class="margin-top">
                    <div class="flex row align-center">
                        <h4 for="filter-by">Networks</h4>
                        <div class="right-align flex row justify-center">
                            <p onClick="selectAllNetworks()" class="selector label right-align">Select All </p>
                            <p onClick="deselectAllNetworks()" class="selector label right-align">&nbsp; Deselect All </p>

                        </div>
                    </div>
                    <?php
                        $defaultNetworks = ['1','2'];
                        $supportedNetworks = $this->request->getQuery('networks', $defaultNetworks);
                    ?>
                    <ul style="margin-left: 1.5rem;">
                        <!-- POLKADOT -->
                        <li onClick="checkBox(this, polkadot)" class="checkbox-list network checked flex row align-center">
                            <div class="checkbox">
                                <div class="check" style="width: 75px;"></div>
                            </div>
                            <div class="icon dot"></div>
                            <p>Polkadot</p>
                        </li>

                        <!-- KUSAMA -->
                        <li onClick="checkBox(this, kusama)" class="checkbox-list network checked flex row align-center">
                            <div class="checkbox">
                                <div class="check" style="width: 75px;"></div>
                            </div>
                            <div class="icon ksm"></div>
                            <p>Kusama</p>
                        </li>

                        <!-- KUSAMA -->
                        <li onClick="checkBox(this, moonbeam)" class="checkbox-list network checked flex row align-center">
                            <div class="checkbox">
                                <div class="check" style="width: 75px;"></div>
                            </div>
                            <div class="icon glmr"></div>
                            <p>Moonbeam</p>
                        </li>
                    </ul>
                    <div style="display: none;">
                        <input class="checkbox-list-hidden network" type="checkbox" name="networks[]" value="2" id="polkadot" <?= in_array('2', $supportedNetworks) ? 'checked' : '' ?>><label for="Polkadot">Polkadot</label><br>
                        <input class="checkbox-list-hidden network" type="checkbox" name="networks[]" value="1" id="kusama" <?= in_array('1', $supportedNetworks) ? 'checked' : '' ?>><label for="Kusama">Kusama</label><br>
                        <input class="checkbox-list-hidden network" type="checkbox" name="networks[]" value="3" id="moonbeam" <?= in_array('3', $supportedNetworks) ? 'checked' : '' ?>><label for="Kusama">Kusama</label><br>
                    </div>
                </div>
                <br/>
    			  <button class="full-width" type="submit">Filter</button>
			      </form>
        </div>
	  </div>

<div class="columns flex col">
    <!-- ANALYTICS OVERVIEW CONTAINER -->
    <div class="overview-container">
        <div class="column is-12  is-offset-1" style="position: relative;">
            <div>
                <h1  style="font-size: 2rem;"> Spend by Category</h1>
                <br/>
            </div>
            <div class="line" style="margin: 0;"></div>
        </div>
    </div>

    <!-- PLACE CONTENT INSIDE THE BELOW DIV -->
    <div class="column is-12  is-offset-1">

        <div id="chartContainer" class="container" style="width: 1200px; margin: 0 auto;">
            <canvas id="myChart"></canvas>
            <div id="myDataTableWrapper">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Network</th>
                            <th>Ref Number</th>
                            <th>Category</th>
                            <th>Track</th>
                            <th>Amount (USD)</th>
                            <th>Title</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    <!-- Data goes here -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- END OF CONTAINER -->
</div>






<script type="text/javascript">
    var rawData = <?php echo $aggregatedData; ?>;
    var responseData = <?php echo $response_data; ?>;
    console.log('Raw data:', rawData);
</script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@latest/dist/chartjs-plugin-zoom.min.js"></script>

<!-- Include your custom script -->
<script src="/js/chart-categories.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<script>
    function showContent(obj){
        const dropdown_content = obj.nextElementSibling;
        if(dropdown_content.style.display === "none"){
            console.log("Currently showing");
            dropdown_content.style.display = "block";
        }
        else{
            console.log("Currently Hiding");
            dropdown_content.style.display = "none";
        }
    }

</script>

<script type="text/javascript">
    //Check Hidden Checkbox
    function checkBox(element, formBox){
        if(formBox.checked == true){
            element.classList.remove('checked');
            formBox.checked = false;
        }
        else{
            element.classList.add('checked');
            formBox.checked = true;
        }
    }
    function selectAllNetworks(){
        const checkboxes = document.querySelectorAll(".checkbox-list.network");
        const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.network");

        for(var i = 0; i < checkboxes.length; i++){
            hiddenboxes[i].checked = true;
            checkboxes[i].classList.add('checked');
        }
    }
    function selectAll(){
        const checkboxes = document.querySelectorAll(".checkbox-list.category");
        const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.category");

        for(var i = 0; i < checkboxes.length; i++){
            hiddenboxes[i].checked = true;
            checkboxes[i].classList.add('checked');
        }
    }

    function deselectAllNetworks(){
        const checkboxes = document.querySelectorAll(".checkbox-list.network");
        const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.network");

        for(var i = 0; i < checkboxes.length; i++){
            hiddenboxes[i].checked = true;
            checkboxes[i].classList.remove('checked');
        }
    }

    
    function deselectAll(){
        const checkboxes = document.querySelectorAll(".checkbox-list.category");
        const hiddenboxes = document.querySelectorAll(".checkbox-list-hidden.category");

        for(var i = 0; i < checkboxes.length; i++){
            hiddenboxes[i].checked = true;
            checkboxes[i].classList.remove('checked');
        }
    }


    //Click to expand dropdown
    let first_item = "";
    function init(){
        //CHECK FOR CATEGORIES
        let checkboxlist = document.querySelectorAll('.checkbox-list');
        let checkbox_hidden = document.querySelectorAll('.checkbox-list-hidden');
        for(var i = 0; i < checkbox_hidden.length; i++){
            if(checkbox_hidden[i].checked == false){
                checkboxlist[i].classList.remove('checked');
            }
        }
        let items = document.getElementById('sort-by').children;
        for(var i = 0; i < items.length; i++){
            items[i].addEventListener("click", function() {
                console.log(this);
                let items = document.getElementById('sort-by').children;
                const temp = items[0].innerHTML;
                console.log(temp.innerHTML);
                items[0].innerHTML = this.innerHTML;
                

                
            });
        };
    }
    init();
    function expandDropdown(id){
        //Get dropdown
        const dropdown_container = document.getElementById(id);
        const length = dropdown_container.children.length;
        for(var i = 1; i < length; i++){
            dropdown_container.children[i].style.display = 'block';
        }

    }
    
    //Exit when clicked out
    window.addEventListener('click', function(e){
        console.log(e.target);
        if (!document.getElementById('sort-by').contains(e.target) && (!document.getElementById('filter-by').contains(e.target))){
        
            let sortItems = document.getElementById('sort-by').children; //the same code you've used to hide the menu
            let filterItems = document.getElementById('filter-by').children;

            //Skip the first one
            for(var i = 1; i < sortItems.length; i++){
                sortItems[i].style.display = "none";
            }

            for(var i = 1; i < filterItems.length; i++){
                filterItems[i].style.display = 'none';
            }
        } 
    })
</script>


