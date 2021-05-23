<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Save as template?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="template-name" class="col-form-label">Template name:</label>
                        <input type="text" class="form-control" id="template-name" onkeyup="updateTemplateName()"
                               onchange="updateTemplateName()">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" onclick="saveTemplateMestimate()">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_new_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Do you want redirect to create form?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">Existing estimates are not automatically saved.<br/>
                    Are you sure you want to switch to the new create form?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="createNew()">OK</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="send_email_mestimate" tabindex="-1" role="dialog" aria-labelledby="sendEmailMestimate"
     aria-hidden="true">

</div>
