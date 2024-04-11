<div wire:poll.15s="loadData" class="portlet">
    <h4 class="portlet-title"><u>Daily Stats</u></h4>
    @if ($balance >= 5000)
        <div class="alert alert-danger" role="alert">
            Your online wallet balance exceeds 5000 MARS. Please store them safely offline.
        </div>
    @endif
    <div class="portlet-body">
        <p>Quick overview over your Marscoin account.</p>
        <hr>
        <table class="table keyvalue-table">
            <tbody>
                <!-- Data rows with dynamic updates -->
                <tr>
                    <td class="kv-key"><i title="{{ $public_addr }}" class="fa fa-money kv-icon kv-icon-primary"></i> Balance</td>
                    <td class="kv-value">
                        <div wire:init="loadData">
                            @if (!$isLoaded)
                                <div class="spinner-border text-primary" role="status">
                                    <span>Loading...</span>
                                </div>
                            @else
                                <span>{{ number_format($balance, 4) }}</span> MARS
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="kv-key"><i
                            class="fa fa-angle-double-right kv-icon kv-icon-secondary"></i>
                        Received</td>
                    <td class="kv-value">
                        <div wire:init="loadData">
                            @if (!$isLoaded)
                                <div class="spinner-border text-primary" role="status">
                                    <span>Loading...</span>
                                </div>
                            @else
                                <span>{{ number_format($received, 4) }}</span> MARS
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="kv-key"><i
                            class="fa fa-angle-double-left kv-icon kv-icon-tertiary"></i>Sent
                    </td>
                    <td class="kv-value">
                        <div wire:init="loadData">
                            @if (!$isLoaded)
                                <div class="spinner-border text-primary" role="status">
                                    <span>Loading...</span>
                                </div>
                            @else
                                <span>{{ number_format($sent, 4) }}</span> MARS
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="kv-key"><a href="/forum"><i class="fa  fa-wechat kv-icon kv-icon-default"></i> Forum Recently</a></td>
                    <td class="kv-value">{{$forum_count}}</td>
                </tr>
                <tr>
                    <td class="kv-key"><a href="/congress/voting"><i class="fa fa-bank kv-icon kv-icon-default"></i> Open Proposals</a></td>
                    <td class="kv-value">{{$proposal_count}}</td>
                </tr>
                <tr>
                    <td class="kv-key"><a href="/citizen/all"><i class="fa  fa-universal-access kv-icon kv-icon-default"></i> Citizen Status</a></td>
                    <td class="kv-value">{{$citizen_status}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>