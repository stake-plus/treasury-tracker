<?= $this->Form->button('Connect', ['id' => 'connect']) ?>
<BR>
<div id="stuff">
Connect first!
</div>
<BR>
<?= $this->Form->select('Address', [], ['empty' => true, 'id' => 'address']) ?>
<?= $this->Form->button('Register', ['id' => 'register']) ?>
<?= $this->Form->create(null, ['id' => 'registerForm', 'url' => ['controller' => 'Users', 'action' => 'register']]) ?>
<?= $this->Form->hidden('address', ['id' => 'addressField']) ?>
<?= $this->Form->hidden('signature', ['id' => 'signatureField']) ?>
<?= $this->Form->end() ?>
