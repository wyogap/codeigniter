<script type="text/javascript" defer> 

var selected_key = 0;

$(document).ready(function() {

    //use user-select event instead of select/deselect to avoid being triggerred because of API
    dt_{$tbl.table_id}.on('select.dt deselect.dt', function() {
        let data = dt_{$tbl.table_id}.rows({
            selected: true
        }).data();

        if (data.length == 0) {
            //on deselect all, clear subtables
            {foreach $subtables as $subtbl}
            dt_{$subtbl.crud.table_id}.clear().draw();
            {/foreach}
        } else {
            //on select, reload subtables
            {foreach $subtables as $subtbl}
            selected_key = data[0]['{$subtbl.table_key_column}'];
            dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}/" +selected_key);
            dt_{$subtbl.crud.table_id}.ajax.reload();
            {/foreach}
        }

    });

});

</script>

{foreach $subtables as $subtbl}
    {include file="crud/_js-crud-table.tpl" tbl=$subtbl.crud}
{/foreach}
