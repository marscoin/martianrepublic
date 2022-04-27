<h3 class="content-title"><u>Create a Proposal</u></h3>
<form class="form account-form" method="POST" action="/congress/createproposal">
    <div class="row">
        <div class="form-group">

            <div class="col-lg-8">
                <label for="title">Title *</label>
                <input type="title" id="title" name="title" class="form-control parsley-validated" data-required="true">


                <label for="textarea-input">Description *</label>
                <textarea type="description" data-required="true" data-minlength="5" name="description" id="description"
                    cols="10" rows="180" style="min-height: 986px;" class="form-control"></textarea>

                <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 15px">

                </div>

            </div>

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">

                        <h4> Sponsor: <strong> {{ $fullname }} </strong></h4>

                        <br />

                        <label for="preset">Proposal Presets: </label>
                        <select name="preset" id="preset">
                            <option value="poll">
                            Certified Poll
                            </option>
                            <option value="ordinance">
                            Ordinance
                            </option>
                            <option value="regulation">
                            Regulation  
                            </option>
                            <option value="statute">
                            Statute
                            </option>
                            <option value="law">
                            Law
                            </option>
                            <option value="amendment">
                            Amendment
                            </option>
                            <option value="custom">
                            Custom
                            </option>
                        </select>
                        <div class="descriptor-text" id="amendment-descriptor" style="display: none;">Amendment: Runtime: 1 Month - Requirement: 90% of Citizenry - Threshold: 75% - Expiration: None.</div>
                        <div class="descriptor-text" id="law-descriptor" style="display: none;">Law: Runtime: 1 Month - Requirement: 80% of Citizenry - Threshold: 65% - Expiration: 4 years.</div>
                        <div class="descriptor-text" id="statute-descriptor" style="display: none;">Statute: Runtime: 2 Weeks - Requirement: 75% of Citizenry - Threshold: 60% - Expiration: 2 years.</div>
                        <div class="descriptor-text" id="regulation-descriptor" style="display: none;">Regulation: Runtime: 2 Weeks - Requirement: 70% of Citizenry - Threshold: 60% - Expiration: 1 year.</div>
                        <div class="descriptor-text" id="ordinance-descriptor" style="display: none;">Ordinance: Runtime: 2 Weeks - Requirement: 60% of Citizenry - Threshold: 55% - Expiration: 1 year.</div>
                        <div class="descriptor-text" id="poll-descriptor" style="display: none;">Certified Poll: Runtime: 1 Week - Requirement: 5% of Citizenry - Threshold: 51% - Expiration: None.</div>


                        <hr>
                        <div class="">
                            <h4>Total Citizen Committment</h4>
                            <p>Citizens can submit proposals to the community. Every citzen endorsed member of the general Martian public ("citizen") can request a ballot to participate in any proposal submitted. </p>
                            <p>The criteria you selected above require a <b><u><span id="req_amount"></span></u></b> percentage of the entire Martian Republic citizenry to partcipate in this proposal. The duration for casting a vote on this 
                            proposal is set to <b><u><span id="req_duration"></span></u></b>. To pass successfully and be adopted at least <b><u><span id="req_threshold"></span></u></b> have to vote in favor of this proposal. This would indicate that 
                            <b><u><span id="req_total"></span></u></b> percentage of the population would support this proposal. It will automatically expire after <b><u><span id="req_expiration"></span></u></b>. Any proposal that reaches the threshold of required participants 
                            via ballots requested will automatically be considered a bill. Any bill that passes the threshold of required votes becomes part of the Constitution (if it is a code change to the system) or an active piece of legislation. Any such law can 
                            be amended or terminated with the same or more citizen voting in favor of a change before it naturally expires.</p>

                        </div>

                        <label>Custom:</label>
                        <div class="price-box">

                            <form class="form-horizontal form-pricing" role="form">

                                <div class="price-slider">
                                    <h4 class="great">Participation</h4>
                                    <span>Minimum 5% is required</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider"></div>
                                    </div>
                                </div>
                                <div class="price-slider">
                                    <h4 class="great">Duration</h4>
                                    <span>Minimum 1 day is required</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider2"></div>
                                    </div>
                                </div>
                                <div class="price-slider">
                                    <h4 class="great">Threshold</h4>
                                    <span>Minimum 51% required to pass</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider3"></div>
                                    </div>
                                </div>
                                <div class="price-slider">
                                    <h4 class="great">Expiration</h4>
                                    <span>Default: 1 Martian year</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider4"></div>
                                    </div>
                                </div>

                                <input type="hidden" id="amount" class="form-control">
                                <input type="hidden" id="duration" class="form-control">
                                <input type="hidden" id="threshold" class="form-control">
                                <input type="hidden" id="expiration" class="form-control">

                            </form>
                        </div>
                        

                    </div>


                </div>


            </div>
        </div>

    </div>
    {{-- <button type="submit" class="btn-lg btn-primary">Submit</button> --}}
    <div>
        <span id="form-message" style="color:#d74b4b; font-weight: 600"> </span>
    </div>
    <a data-toggle="modal" href="#proposalModal" id="proposalModalBtn"
        class="btn-lg btn-primary demo-element">Confirm</a>


    <div id="proposalModal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Basic Modal</h3>
                </div> <!-- /.modal-header -->

                <div class="modal-body">
                    <div class="modal-body-box">
                        <h5>Category: </h5>
                        <p class="modal-category"> </p>
                    </div>


                    <div class="modal-body-box">
                        <h5> Description: </h5>
                        <p class="modal-description"></p>
                    </div>

                    <div class="modal-body-box">
                        <h5> Configuration: </h5>
                        <p class="modal-configuration"></p>
                    </div>

                    <div class="modal-message" style="display: none">
                        <i class="fa fa-times-circle"></i>
                        <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                        <span id="modal-message-success" style="font-weight: 600"> </span>
                    </div>
                </div> <!-- /.modal-body -->
                <h5 class="transaction-hash" style="text-align: center;"></h5>

                <div class="modal-footer">
                    <button id="submit-proposal" type="submit" class="btn btn-primary">Submit Proposal</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here" style="display: none" id="loading">
                </div> <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>



</form>
