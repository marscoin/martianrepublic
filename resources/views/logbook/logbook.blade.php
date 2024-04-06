<h3 class="content-title"><u>Create a log entry</u></h3>
<form class="form account-form" method="POST" action="/logbook/createentry">
    <div class="row">
        <fieldset class="content-group">

            <div class="col-lg-8">
                <label for="title">Title *</label>
                <input type="title" id="title" name="title" class="form-control parsley-validated" data-required="true">
                <label for="textarea-input">Entry</label>
                <textarea type="description" data-required="true" data-minlength="5" name="description" id="description"
                    cols="10" rows="180" style="min-height: 686px;" class="form-control"></textarea>

                <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 15px">

                </div>

            </div>

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                    <div class="form-group">
                        <input type="file" 
                            class="my-pond"
                            name="filenames[]"
                            multiple
                            data-max-file-size="3MB"
                            data-max-files="3" />
                    </div> 
                    </div>
                </div>
            </div>
        </fieldset>




    </div>
    <div>
        <span id="form-message" style="color:#d74b4b; font-weight: 600"> </span>
    </div>
    <a data-toggle="modal" href="#" id="saveLogLocalBtn" class="btn-lg btn-success demo-element pull-left">Publish to IPFS</a>
    <input type="hidden" id="ipfs_path" value="">

    <div id="logModal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Basic Modal</h3>
                </div> <!-- /.modal-header -->

                    <div class="modal-message" style="display: none">
                        <i class="fa fa-times-circle"></i>
                        <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                        <span id="modal-message-success" style="font-weight: 600"> </span>
                    </div>
                </div> <!-- /.modal-body -->
                <h5 class="transaction-hash" style="text-align: center;"></h5>

                <div class="modal-footer">
                    <button id="submit-log" type="submit" class="btn btn-primary">Submit log entry</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here" style="display: none" id="loading">
                </div> <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->


</form>
