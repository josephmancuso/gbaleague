<!-- Footer Bottom Area
============================================ -->
<div class="footer-bottom">
	<div class="container">
		<div class="row">
			<!-- Copyright -->
			<div class="copyright col-sm-6 col-xs-12 text-left">
				<p>Copyright &copy; <a href="https://hastech.company/" target="_blank">GBALeague.com</a> 2017 All right reserved.</p>
			</div>
		</div>
	</div>
</div>

<!-- JS -->
<!-- jQuery JS
============================================ -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<!-- Bootstrap JS
============================================ -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- Mean Menu JS
============================================ -->
<script src="{{ loadStatic('Site') }}/js/mean-menu.js"></script>
<!-- mixitup JS
============================================ -->
<script src="{{ loadStatic('Site') }}/js/mixitup.js"></script>
<!-- ScrollUP JS
============================================ -->
<script src="{{ loadStatic('Site') }}/js/jquery.scrollup.min.js"></script>
<!-- CounterUP JS
============================================ -->
<script src="{{ loadStatic('Site') }}/js/jquery.counterup.min.js"></script>
<!-- Waypoints JS
============================================ -->
<script src="{{ loadStatic('Site') }}/js/waypoints.min.js"></script>
<!-- Main JS
============================================ -->
<script src="{{ loadStatic('Site') }}/js/main.js"></script>
<script src="{{ loadStatic('Site') }}/js/pickadate/lib/picker.js"></script>
<script src="{{ loadStatic('Site') }}/js/pickadate/lib/picker.date.js"></script>
<script src="{{ loadStatic('League') }}/js/tiercontrol.js"></script>
<script src="{{ loadStatic('Site') }}/js/summernote/dist/summernote.js"></script>

<script>
$('.datepicker').pickadate({
	min: new Date(),
});

$('.summernote').summernote({
	minHeight: '200px',
});
</script>

</body>
</html>