<h3 class="content-title"><u>Active Proposals</u></h3>
<div class="row">
  <div class="col-md-9">

@if ($active->isEmpty())
    <div class="alert alert-info">
        Currently no active proposals underway. Consider launching a <a href="/congress/voting/new" style="color: white;">new proposal</a>!
    </div>
@else
    @foreach ($active as $proposal)
    <div class="post" style="border-bottom: 1px dotted #ccc;margin-bottom: 50px;padding-bottom: 50px;">
            <div class="post-aside">
                @php
                    $createdAt = \Carbon\Carbon::parse($proposal->mined);
                @endphp
                <div class="post-date">
                    <span class="post-date-day">{{ $proposal->id }}</span>
                    <span class="post-date-month">#</span>
                    <span class="post-date-year">Bill</span>
                </div>
                <a href="/forum/t/{{ $proposal->discussion }}" class="post-comment">
                {{$proposal->post_count}}
                </a>
            </div> 
            <div class="post-main">
                <h3 class="post-title"><a href="/congress/proposal/{{$proposal->id}}">{{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a></h4>
                <h5>Proposal: {{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <a  target="_blank" href="{{$proposal->ipfs_hash}}"><i class="fa-solid fa-link"></i></a></h5>
                <div class="post-content">      
                <p>{{substr($proposal->description, 0, 400)}}<a href="/congress/proposal/{{$proposal->id}}"> Read More...</a></p>
                    <div class="row">
                        <div class="col-sm-4" style="padding-top: 14px;">
                            <a href="#" class="btn btn-success">Voting in Progress</a>
                        </div>
                        <div class="col-sm-4">
                            @if(!$proposal->mined && $proposal->active)
                            @else
                            @php
                                $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                            @endphp
                            <x-countdown-timer :proposal-id="$proposal->id" :end-time="$endTime" :start-time="$proposal->mined" />
                            @endif
                        </div>
                        <div class="col-sm-4">
                            @if(!$proposal->mined && $proposal->active)
                            @else
                            @livewire('voting-progress', ['proposalId' => $proposal->id])
                            @endif
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    @endforeach
    @endif
    </div>

    <div class="col-md-3">
        <p>The <b>Martian Congressional Republic</b> consists of known <a href="/citizen/all">citizens</a> who discuss public matters ("res publica") in an open and transparent way. They vote on changes - including the very code that runs this application ("<b>The Constitution</b>") - in an equally transparent yet fully anonymous way. Every vote is cryptographically secured and can be audited by everyone. </p> 

        <p>Fair, transparent, immutable and auditable votes are the outcome. Our congressional archive lists all passed and failed proposals, bills, amendments and references all discussions. The Martian Congressional Republic embodies democracy as a living organism shared directly by all members of the Republic - allowing better ideas to win out and move Martian civilization forward. </p>

    </div>
</div>


<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/dist/my_bundle.js"></script>
@include('partials.mars-tx')
<script>
    $(document).ready(function() {

        $('.dynamic-vote-modal').on('hidden.bs.modal', function () {
            location.reload();
        })

        const senderAddr = "{{$public_address}}".trim();

        ////// Proposal Vote Logic..........
        // YES VOTE
        $(".vote-modal-btn-yes").click(async (e)=>
        {
           await prepareAndSign(e, "yes")
        })

        // NO VOTE
        $(".vote-modal-btn-no").click(async (e)=>
        {
           await prepareAndSign(e, "no")
        })

        // NULL VOTE
        $(".vote-modal-btn-null").click(async (e)=>
        {
            await prepareAndSign(e, "null")
        })

        // Prepare fee estimate, then sign and broadcast on confirm
        const prepareAndSign = async (e, vote) =>
        {
            const message = "PR" + e.target.id

            try {
                const io = await MarsWallet.getUtxos([senderAddr], senderAddr, MarsWallet.DUST_AMOUNT);
                const fee = io.fee / 100000000;
                const mars_amount = 0.001;
                const total_amount = fee + parseInt(mars_amount);

                $(".modal-cost").text(total_amount + " MARS")
            } catch (err) {
                console.warn("Could not estimate fee:", err.message);
                $(".modal-cost").text("~0.001 MARS")
            }

            $(`#submit-${vote}-vote`).click(async(e)=>
            {
                $("#loading").show()
                $(`#submit-${vote}-vote`).hide()
                try {
                    const mnemonic = WalletKey.get().trim();
                    const result = await MarsWallet.signCivicAction(senderAddr, mnemonic, message);
                    $("#loading").hide()
                    $("#modal-message-success").show()
                    $(".transaction-hash-link").attr("href",
                        "https://explore.marscoin.org/tx/" + result.txid)
                    $(".transaction-hash").text("" + result.txid)

                } catch (err) {
                    $("#loading").hide()
                    handleError(MarsWallet.friendlyError(err))
                    throw err;
                }
            })
        }

    })
</script>
