<div id="footer">
   <div class="footer-nav col-md-8 col-sm-6">
      <a href="#" target="_blank" class="footer-nav-item">About</a>
      <a href="#" target="_blank" class="footer-nav-item">Support</a>
      <a href="#" target="_blank" class="footer-nav-item">Contact</a>
   </div>
   <div class="copyright text-end col-md-4 col-sm-6">
      2022&nbsp;©&nbsp;<a href="#" target="_blank">Kngell</a>
   </div>

</div>

<!-- Librairies -->
<script type="text/javascript" src="<?= $this->asset('js/librairies/adminlib', 'js') ?? ''?>">
</script>
<!-- Common vendor -->
<script type="text/javascript" src="<?= $this->asset('commons/admin/commonVendor', 'js') ?? ''?>">
</script>
<!-- Common custom modules -->
<script type="text/javascript" src="<?= $this->asset('commons/admin/commonCustomModules', 'js') ?? ''?>">
</script>
<!-- Plugins -->
<script type="text/javascript" src="<?= $this->asset('js/plugins/adminplugins', 'js') ?? ''?>">
</script>
<!-- Mainjs -->
<script type="text/javascript" src="<?= $this->asset('js/admin/main/main', 'js') ?? ''?>">
</script>
<!-- Custom js -->
<?= $this->content('footer'); ?>
</body>

</html>