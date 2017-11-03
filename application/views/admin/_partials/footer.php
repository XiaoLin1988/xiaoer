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

<script>
    var selIndex = 0;
    $('ul#main-menu li').on('click', function(){
        selIndex = $(this).index();
        $('ul#main-menu li').each(function(index){
            if (index != selIndex) {
                $(this).find('a').removeClass('active-menu');
            } else {
                $(this).find('a').addClass('active-menu');
            }
        });
    });
    /*
    $('ul#main-menu li').each(function(index){
        alert(index);
    });
    */
</script>

</body>
</html>