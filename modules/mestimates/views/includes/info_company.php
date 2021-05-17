<div class="row">


    <h3 class="bold"><?php echo _l('Projects'); ?></h3>
    <div class="col-md-12">
        <div class="f_client_id">
            <div class="form-group select-placeholder">
                <select id="project_id" name="project_id" class="selectpicker" data-live-search="true"
                        data-width="100%">
                    <?php
                    if (isset($projects)) {
                        echo ' <option value="0"></option>';
                        for ($i = 0; $i < count($projects); $i++) {
                            $selected = (isset($mestimate) && $mestimate->project_id == $projects[$i]['id']) ? 'selected' : '';
                            ?>
                            <option value="<?= $projects[$i]['id'] ?>" <?= $selected ?>><?= $projects[$i]['name'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <h3 class="bold"><?php echo _l('client_information'); ?></h3>
    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="firstname">
            <label for="firstname" class="control-label"><?php echo _l('firstname'); ?></label>
            <input type="text" id="firstname" name="firstname" class="form-control" disabled="1"
                   value="<?= (isset($contact) ? $contact->firstname : ''); ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="lastname">
            <label for="lastname" class="control-label"><?php echo _l('lastname'); ?></label>
            <input type="text" id="lastname" name="lastname" class="form-control" disabled="1"
                   value="<?= (isset($contact) ? $contact->lastname : ''); ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="title">
            <label for="title" class="control-label"><?php echo _l('title'); ?></label>
            <input type="text" id="title" name="title" class="form-control" disabled="1"
                   value="<?= (isset($contact) ? $contact->title : ''); ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="email">
            <label for="email" class="control-label"><?php echo _l('email'); ?></label>
            <input type="text" id="email" name="email" class="form-control" disabled="1"
                   value="<?= (isset($contact) ? $contact->email : ''); ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="phonenumber">
            <label for="phonenumber" class="control-label"><?php echo _l('phonenumber'); ?></label>
            <input type="text" id="phonenumber" name="phonenumber" class="form-control" disabled="1"
                   value="<?= (isset($contact) ? $contact->phonenumber : ''); ?>">
        </div>
    </div>
</div>
<div class="row">
    <h3 class="bold"><?php echo _l('property_info'); ?></h3>

    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="company">
            <label for="company" class="control-label"><?php echo _l('company'); ?></label>
            <input type="text" id="company" name="company" class="form-control" disabled="1"
                   value="<?= (isset($client) ? $client->company : ''); ?>">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="address">
            <label for="address" class="control-label"><?php echo _l('address'); ?></label>
            <input type="text" id="address" name="address" class="form-control" disabled="1"
                   value="<?= (isset($client) ? $client->address : ''); ?>">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="city">
            <label for="city" class="control-label"><?php echo _l('city'); ?></label>
            <input type="text" id="city" name="city" class="form-control" disabled="1"
                   value="<?= (isset($client) ? $client->city : ''); ?>">
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group" app-field-wrapper="country">
            <label for="country" class="control-label"><?php echo _l('country'); ?></label>
            <input type="text" id="country" name="country" class="form-control" disabled="1"
                   value="<?= (isset($client) ? $client->country : ''); ?>">
        </div>
    </div>
</div>

<?php
if (isset($rtype) && $rtype === 'json') {
    ?>
    <script type="text/javascript">
        init_selectpicker();
    </script>
    <?php
}
?>