<?php

require __DIR__ . '/db.php';

// Lấy các giá trị mạng xã hội cùng lúc
$contactNames = ['facebook', 'twitter', 'instagram'];
$contacts = [];

foreach ($contactNames as $name) {
    $stmt = $pdo->prepare("SELECT value FROM contact WHERE name = ?");
    $stmt->execute([$name]);
    $contacts[$name] = $stmt->fetchColumn();
}

?>
<footer class="footer section text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="social-media">
                    <?php if (!empty($contacts['facebook'])): ?>
                        <li>
                            <a href="https://www.facebook.com/<?= htmlspecialchars($contacts['facebook']) ?>">
                                <i class="tf-ion-social-facebook"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($contacts['instagram'])): ?>
                        <li>
                            <a href="https://www.instagram.com/<?= htmlspecialchars($contacts['instagram']) ?>">
                                <i class="tf-ion-social-instagram"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($contacts['twitter'])): ?>
                        <li>
                            <a href="https://www.twitter.com/<?= htmlspecialchars($contacts['twitter']) ?>">
                                <i class="tf-ion-social-twitter"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="footer-menu text-uppercase">
                    <li><a href="contact.html">CONTACT</a></li>
                    <li><a href="/products">SHOP</a></li>
                    <li><a href="/privacy-policy">PRIVACY POLICY</a></li>
                    <li><a href="/faq">FAQ</a></li>
                </ul>

                <p class="copyright-text">
                    &copy; <script>document.write(new Date().getFullYear());</script>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="views/plugins/jquery/dist/jquery.min.js"></script>
<script src="views/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="views/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
<script src="views/plugins/ekko-lightbox/dist/ekko-lightbox.min.js"></script>
<script src="views/plugins/syo-timer/build/jquery.syotimer.min.js"></script>
<script src="views/plugins/slick/slick.min.js"></script>
<script src="views/plugins/slick/slick-animation.min.js"></script>
<script src="views/js/script.js"></script>

<?php if (isset($_SESSION['name'])): ?>
    <script>
        window.intercomSettings = {
            api_base: "https://api-iam.intercom.io",
            app_id: "",
            name: "<?= $_SESSION['name'] ?>",
            email: "<?= $_SESSION['email'] ?>",
            created_at: "<?= $_SESSION['created-time'] ?>"
        };
    </script>
<?php else: ?>
    <script>
        window.intercomSettings = {
            api_base: "https://api-iam.intercom.io",
            app_id: ""
        };
    </script>
<?php endif; ?>

<script>
    (function () {
        var w = window;
        var ic = w.Intercom;
        if (typeof ic === "function") {
            ic('reattach_activator');
            ic('update', w.intercomSettings);
        } else {
            var d = document;
            var i = function () {
                i.c(arguments);
            };
            i.q = [];
            i.c = function (args) {
                i.q.push(args);
            };
            w.Intercom = i;
            var l = function () {
                var s = d.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://widget.intercom.io/widget/qq33os1d';
                var x = d.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            };
            if (document.readyState === 'complete') {
                l();
            } else if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })();
</script>

</body>
</html>
