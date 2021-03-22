<script type="text/javascript">
    function view_edit_column(e, dt, node, conf) {
        alert ('Kolom Editor: ' + JSON.stringify(conf));

        //dt.hide(undefined,false)
    }

    function view_filter_column(e, dt, node, conf) {
        alert ('Kolom Filter: ' + JSON.stringify(conf));
    }

    function view_fkey_column(e, dt, node, conf) {
        alert ('Kolom Lookup: ' + JSON.stringify(conf));
    }

</script>
