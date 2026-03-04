<h3 class="content-title"><u>My Inventory</u></h3>

<div class="row">
    <div class="col-md-12">
        @if(count($myItems) === 0)
        <div style="text-align: center; padding: 40px 20px; color: #888;">
            <i class="fa fa-cubes" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
            <h4>No inventory items yet</h4>
            <p>Start tracking colony resources and equipment. Register your first item.</p>
            <a href="#AddItem" data-toggle="tab" class="btn btn-primary" onclick="$('#invTab a[href=\'#AddItem\']').tab('show');"><i class="fa fa-plus"></i> Add First Item</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered datatable myitems-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Condition</th>
                        <th>Location</th>
                        <th>Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->description)
                            <br><small style="color: #888;">{{ Str::limit($item->description, 60) }}</small>
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
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-default editItemBtn" data-toggle="modal" href="#editItemModal"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-description="{{ $item->description }}"
                                    data-category="{{ $item->category }}"
                                    data-quantity="{{ $item->quantity }}"
                                    data-unit="{{ $item->unit }}"
                                    data-location="{{ $item->location }}"
                                    data-condition="{{ $item->condition }}"
                                    title="Edit"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger deleteItemBtn" data-toggle="modal" href="#deleteItemModal" data-id="{{ $item->id }}" title="Delete"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Edit Item Modal --}}
<div id="editItemModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editItemForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Edit Item</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name *</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" rows="3" class="form-control" maxlength="2000"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_category">Category *</label>
                                <select name="category" id="edit_category" class="form-control" required>
                                    @foreach($categories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_condition">Condition *</label>
                                <select name="condition" id="edit_condition" class="form-control" required>
                                    @foreach($conditions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_quantity">Quantity *</label>
                                <input type="number" id="edit_quantity" name="quantity" class="form-control" required min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_unit">Unit</label>
                                <input type="text" id="edit_unit" name="unit" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_location">Location</label>
                                <input type="text" id="edit_location" name="location" class="form-control" maxlength="255">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteItemModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirm Deletion</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item from the inventory? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteItemForm" method="POST" action="">
                    @csrf
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
