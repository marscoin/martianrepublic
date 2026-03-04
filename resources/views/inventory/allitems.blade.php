<h3 class="content-title"><u>Colony Resource Registry</u></h3>
<p style="color: #888; margin-bottom: 20px;">All items registered by Martian Republic citizens across the colony.</p>

<div class="row">
    <div class="col-md-12">
        @if(count($allItems) === 0)
        <div style="text-align: center; padding: 40px 20px; color: #888;">
            <i class="fa fa-globe" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
            <h4>No items in the colony registry</h4>
            <p>Be the first to register colony resources and equipment.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered datatable allitems-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Condition</th>
                        <th>Location</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->description)
                            <br><small style="color: #888;">{{ Str::limit($item->description, 80) }}</small>
                            @endif
                        </td>
                        <td><span class="label label-default">{{ $categories[$item->category] ?? $item->category }}</span></td>
                        <td>{{ $item->quantity }}{{ $item->unit ? ' ' . $item->unit : '' }}</td>
                        <td>
                            @php
                                $condColors = ['new'=>'success','good'=>'info','fair'=>'default','worn'=>'warning','damaged'=>'danger','decommissioned'=>'danger'];
                            @endphp
                            <span class="label label-{{ $condColors[$item->condition] ?? 'default' }}">{{ $conditions[$item->condition] ?? $item->condition }}</span>
                        </td>
                        <td>{{ $item->location ?? '—' }}</td>
                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
