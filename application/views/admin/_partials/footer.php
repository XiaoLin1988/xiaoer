        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->

<?php
    foreach ($javascripts['foot'] as $js) {
        $url = starts_with($js, 'http') ? $js : base_url($js);
        echo "<script src='$url'></script>".PHP_EOL;
    }
?>

</body>
</html>