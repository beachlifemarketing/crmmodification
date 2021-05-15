<div class="row">
    <h3 class="bold"><?php echo _l('client_information'); ?></h3>
    <div class="col-md-6">
        <?php $value = (isset($contact) ? $contact->firstname : ''); ?>
        <?php echo render_input('firstname', 'firstname', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($contact) ? $contact->lastname : ''); ?>
        <?php echo render_input('lastname', 'lastname', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($contact) ? $contact->title : ''); ?>
        <?php echo render_input('title', 'title', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($contact) ? $contact->email : ''); ?>
        <?php echo render_input('email', 'email', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($contact) ? $contact->phonenumber : ''); ?>
        <?php echo render_input('phonenumber', 'phone', $value, null, array('disabled' => true)); ?>
    </div>
</div>
<div class="row">
    <h3 class="bold"><?php echo _l('property_info'); ?></h3>
    <div class="col-md-6">
        <?php $value = (isset($client) ? $client->company : ''); ?>
        <?php echo render_input('company', 'company', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($client) ? $client->address : ''); ?>
        <?php echo render_input('address', 'address', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($client) ? $client->city : ''); ?>
        <?php echo render_input('city', 'city', $value, null, array('disabled' => true)); ?>
    </div>
    <div class="col-md-6">
        <?php $value = (isset($client) ? $client->country : ''); ?>
        <?php echo render_input('country', 'country', $value, null, array('disabled' => true)); ?>
    </div>
</div>