<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Martian Republic - Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Martian Republic Resource Inventory">
    <meta name="author" content="The Marscoin Foundation">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
</head>
<body class=" ">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div>
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div>
        </header>
        @include('wallet.mainnav', array('active'=>'inventory'))
        <div class="content">
            <div class="container">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('error') }}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <?php if($wallet_open){ ?>
                <div class="portlet">
                    <div class="portlet-body">

                        <ul id="invTab" class="nav nav-tabs">
                            <li class="active">
                                <a href="#MyItems" data-toggle="tab">My Inventory</a>
                            </li>
                            <li class="">
                                <a href="#AllItems" data-toggle="tab">Colony Registry</a>
                            </li>
                            <li class="">
                                <a href="#AddItem" data-toggle="tab"><i class="fa fa-plus"></i> Add Item</a>
                            </li>
                        </ul>
                        <div id="invTabContent" class="tab-content" style="margin-top: 30px;">

                            {{-- My Items Tab --}}
                            <div class="tab-pane fade active in" id="MyItems">
                                @include('inventory.myitems')
                            </div>

                            {{-- All Items Tab --}}
                            <div class="tab-pane fade" id="AllItems">
                                @include('inventory.allitems')
                            </div>

                            {{-- Add Item Tab --}}
                            <div class="tab-pane fade" id="AddItem">
                                @include('inventory.additem')
                            </div>

                        </div>

                    </div>
                </div>
                <?php }else{ ?>
                <div class="portlet" style="text-align: center; padding: 40px 20px;">
                    <i class="fa fa-lock" style="font-size: 48px; color: var(--mr-text-secondary, #8a8998); margin-bottom: 16px; display: block;"></i>
                    <h3 style="margin-bottom: 12px;">Wallet Required</h3>
                    <p style="color: var(--mr-text-secondary, #8a8998); margin-bottom: 20px;">Please unlock your civic wallet to access the Inventory.</p>
                    <a href="/wallet/dashboard/hd" class="btn btn-lg btn-primary"><i class="fa fa-unlock-alt"></i> Unlock Wallet</a>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
    <footer class="footer">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <script>
    $(document).ready(function() {
        $('.myitems-table').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 25
        });
        $('.allitems-table').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 25
        });
    });

    // Handle edit modal
    $('.editItemBtn').click(function() {
        var item = $(this).data();
        $('#edit_item_id').val(item.id);
        $('#edit_name').val(item.name);
        $('#edit_description').val(item.description);
        $('#edit_category').val(item.category);
        $('#edit_quantity').val(item.quantity);
        $('#edit_unit').val(item.unit);
        $('#edit_location').val(item.location);
        $('#edit_condition').val(item.condition);
        $('#editItemForm').attr('action', '/inventory/' + item.id + '/update');
    });

    // Handle delete confirmation
    var deleteItemId;
    $('.deleteItemBtn').click(function() {
        deleteItemId = $(this).data('id');
        $('#deleteItemForm').attr('action', '/inventory/' + deleteItemId + '/delete');
    });
    </script>
</body>
</html>
