<div class="clearfix"></div>
</div>
<!--/ Content -->
<!-- Footer -->
<footer>
    <div class="copyrights">
        &copy;<?php echo date('Y') . ' <b>' . $title . '</b>'; ?> All Rights Reserved
    </div>
    <!--    <div class="social">
            <a href="#" class="facebook">
                <span class="fa fa-facebook"></span>
            </a>
            <a href="#" class="google">
                <span class="fa fa-google-plus"></span>
            </a>
            <a href="#" class="twitter">
                <span class="fa fa-twitter"></span>
            </a>
            <a href="#" class="youtube">
                <span class="fa fa-youtube"></span>
            </a>
            <a href="#" class="instagram">
                <span class="fa fa-instagram"></span>
            </a>
            <a href="#" class="pinterest">
                <span class="fa fa-pinterest"></span>
            </a>
            <a href="#" class="rss">
                <span class="fa fa-rss"></span>
            </a>
        </div>-->
</footer>
<!--/ Footer -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo assets('blog/js/bootstrap.min.js'); ?>"></script>
<!-- WOW JS -->
<script src="<?php echo assets('blog/js/wow.min.js'); ?>"></script>
<!-- Custom JS -->
<script src="<?php echo assets('blog/js/custom.js'); ?>"></script>
<script>
    // Navbar items
    var currentUrl = window.location.href;
    var segment = currentUrl.split('/').pop();
    $('#nav-' + segment).addClass('active');
// Deleting item
    $('.delete').on('click', function (e) {
        e.preventDefault();
        btn = $(this);
        c = confirm('Are you sure? This action cannot be undone ! Think twice before doing it');
        if (c === true) {
            $.ajax({
                url: btn.data('target'),
                type: 'POST',
                dataType: 'json',
                success: function (r) {
                    window.location.href = r.redirectHome;
                }
            });
        } else {
            return false;
        }
    });
</script>
</body>
</html>