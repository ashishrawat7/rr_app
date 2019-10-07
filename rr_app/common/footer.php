<footer class="pull-left footer">
    <div class="col-md-12" >
  	    <hr class="divider">
  	    <p class="text-center">Copyright &COPY; <?php echo date('Y'); ?> <a target="_blank" href="http://www.jinibrain.com.">jinibrain.com</a></p>
    </div>
</footer>
</div>
</div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/common-main.js"></script>
    <script>
		$(function () {
		$('.navbar-toggle-sidebar').click(function () {
			$('.navbar-nav').toggleClass('slide-in');
			$('.side-body').toggleClass('body-slide-in');
			$('#search').removeClass('in').addClass('collapse').slideUp(200);
		});
	
		$('#search-trigger').click(function () {
			$('.navbar-nav').removeClass('slide-in');
			$('.side-body').removeClass('body-slide-in');
			$('.search-input').focus();
		});
	  });
	</script>
  </body>
</html>
