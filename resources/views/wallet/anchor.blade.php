<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.css">
    <link rel="shortcut icon" href="favicon.ico">
    <script>var current_blob = null;</script>
</head>

<body class=" ">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div> <!-- /.navbar-header -->
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', array('active'=>'citizen'))
        <div class="content">

            <div class="container">

                <p>Anchor test</p>
                <a href="#" id="publish" class="btn btn-primary">Publish</a>
                       
            </div> <!-- /.container -->
        </div> <!-- .content -->
    </div> <!-- /#wrapper -->
    <footer class="footer">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script>
    const Marscoin = {
        mainnet: {
            messagePrefix: "\x19Marscoin Signed Message:\n",
            bech32: "M",
            bip44: 2,
            bip32: {
                public: 0x043587cf,
                private: 0x04358394,
            },
            pubKeyHash: 0x32,
            scriptHash: 0x32,
            wif: 0x80,
        }
    };
    </script>
    @include('partials.mars-tx')
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>


<script type="text/javascript">

$(document).ready(function() {

$("#publish").click(async (e) => {
    e.preventDefault();
    const cid = "QmSyMr2CjV1qS4cBpVv9EpeXZDuaWxMSyY5Z7Hwrpdb1Mi";
    const message = "test_" + cid;
    const senderAddress = "<?=$public_address?>";
    const mnemonic = WalletKey.get().trim();

    try {
        const result = await MarsWallet.signCivicAction(senderAddress, mnemonic, message);
        console.log("Anchor tx broadcast:", result.txid);
        //$("#loading").hide()
        //$(".success-message").show()
        // $(".transaction-hash-link").attr("href",
        //     "https://explore.marscoin.org/tx/" + result.txid)
        //$(".transaction-hash").text("" + result.txid)
    } catch (e) {
        console.error("Anchor error:", MarsWallet.friendlyError(e));
        throw e;
    }
});

});

</script>
</body>

</html>
