<h1>Referenda</h1>

<table>
    <tr>
        <th>Referenda Number</th>
        <th>Finalized</th>
        <th>Track</th>
        <th>Preimage Hash</th>
        <th>Submission Address</th>
        <th>Decision Address</th>
        <th>Ayes</th>
        <th>Nays</th>
        <th>Support</th>
    </tr>

    <?php foreach ($referenda as $referendum): ?>
    <tr>
        <td><button class="table-link" data-href="/network/<?= $network ?>/<?= $referendum->referenda_number ?>"><?= $referendum->referenda_number ?></button></td>
        <td><?= $referendum->final ?></td>
        <td><?= $referendum->track ?></td>
        <td><?= $referendum->preimage_hash ?></td>
        <td><?= $referendum->submission_deposit_address ?></td>
        <td><?= $referendum->decision_deposit_address ?></td>
        <td><?= $referendum->tally_ayes ?></td>
        <td><?= $referendum->tally_nays ?></td>
        <td><?= $referendum->tally_support ?></td>
    </tr>
    <?php endforeach; ?>
</table>

