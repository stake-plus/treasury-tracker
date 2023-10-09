<div class="overview-container flex col">
    <div class="column is-8 is-offset-2" style="position: relative;">
        <div>
            <h1 style="font-size: 2rem;">Relay Chains Info</h1>
            <br/>
        </div>
        <div class="line" style="margin: 0;"></div>
    </div>
	<div class="column is-8 is-offset-2">
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Token Symbol</th>
					<th>Decimal Places</th>
					<th>Governance 1.0</th>
					<th>Treasury Council</th>
					<th>Council Elections</th>
					<th>Open Governance</th>
					<th>Sudo Pallet</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($networks as $network) {
					if ($network->type == "relay") {
						$count_decimals = strlen(substr(strrchr($network->decimal_places, '1'), 1));

						$json = json_decode($network->pallets);
				?>
				<tr>
					<td><?= $network->long_name ?></td>
					<td><?= $network->short_name ?></td>
					<td><?= $network->decimal_places ?> (<?= $count_decimals ?>)</td>
					<td>
						<div class="icon <?php if (isset($json->democracy)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->council)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->elections)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->convictionVoting)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->sudo)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
				</tr>
				<?php } 
						}
				?>
			</tbody>
		</table>
	</div>
    <div class="column is-8 is-offset-2" style="position: relative;">
        <div>
            <h1 style="font-size: 2rem;">Para Chains Info</h1>
            <br/>
        </div>
        <div class="line" style="margin: 0;"></div>
    </div>
	<div class="column is-8 is-offset-2">
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Token Symbol</th>
					<th>Decimal Places</th>
					<th>Governance 1.0</th>
					<th>Treasury Council</th>
					<th>Council Elections</th>
					<th>Open Governance</th>
					<th>Sudo Pallet</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($networks as $network) {
					if ($network->type == "para") {
						$count_decimals = strlen(substr(strrchr($network->decimal_places, '1'), 1));

						$json = json_decode($network->pallets);
				?>
				<tr>
					<td><?= $network->long_name ?></td>
					<td><?= $network->short_name ?></td>
					<td><?= $network->decimal_places ?> (<?= $count_decimals ?>)</td>
					<td>
						<div class="icon <?php if (isset($json->democracy)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->council)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->elections)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->convictionVoting)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
					<td>
						<div class="icon <?php if (isset($json->sudo)) { echo "aye"; } else { echo "nay"; } ?>"></div>
					</td>
				</tr>
				<?php 	} 
							}
				?>
			</tbody>
		</table>
	</div>
</div>

