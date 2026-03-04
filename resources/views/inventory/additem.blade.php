<h3 class="content-title"><u>Register New Item</u></h3>
<form class="form" method="POST" action="/inventory/store">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="form-group">
                <label for="name">Item Name *</label>
                <input type="text" id="name" name="name" class="form-control" required maxlength="255" value="{{ old('name') }}" placeholder="e.g. Solar Panel Array Unit #4">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control" maxlength="2000" placeholder="Detailed description of the item...">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select category...</option>
                            @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="condition">Condition *</label>
                        <select name="condition" id="condition" class="form-control" required>
                            @foreach($conditions as $key => $label)
                            <option value="{{ $key }}" {{ old('condition', 'new') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="quantity">Quantity *</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required min="1" max="999999" value="{{ old('quantity', 1) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" maxlength="50" value="{{ old('unit') }}" placeholder="e.g. kg, pcs, liters">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" class="form-control" maxlength="255" value="{{ old('location') }}" placeholder="e.g. Hab Module A, Bay 3">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="well" style="margin-top: 25px;">
                <h4><i class="fa fa-info-circle"></i> About Inventory</h4>
                <p>Track colony resources and equipment using the immutable ledger. Items can be notarized on the Marscoin blockchain for permanent record-keeping.</p>
                <hr>
                <p><small><i class="fa fa-cube"></i> Items are organized by category and can be filtered, searched, and exported.</small></p>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-plus"></i> Add to Inventory</button>
</form>
