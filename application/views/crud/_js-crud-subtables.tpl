<script type="text/javascript" defer> 

{foreach $subtables as $subtbl}
var selected_key_{$subtbl.crud.table_id} = '';
var selected_label_{$subtbl.crud.table_id} = '';
var data_{$subtbl.crud.table_id} = null;
{/foreach}

$(document).ready(function() {

    {if empty($detail)}
        //use user-select event instead of select/deselect to avoid being triggerred because of API
        dt_{$tbl.table_id}.on('select.dt deselect.dt', function() {
            let data = dt_{$tbl.table_id}.rows({
                selected: true
            }).data();

            if (data.length == 0) {
                //on deselect all, clear subtables
                {foreach $subtables as $subtbl}
                dt_{$subtbl.crud.table_id}.clear().draw();
                selected_key_{$subtbl.crud.table_id} = '';
                data_{$subtbl.crud.table_id} = null;
                {/foreach}
            } else {
                //on select, reload subtables
                {foreach $subtables as $subtbl}
                //master value
                selected_key_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_key_column}'];
                selected_label_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_label_column}'];
                data_{$subtbl.crud.table_id} = data[0];
                dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}/" +selected_key_{$subtbl.crud.table_id});
                {if $subtbl.crud.editor}
                editor_{$subtbl.crud.table_id}.s.ajax = "{$subtbl.crud.ajax}/" +selected_key_{$subtbl.crud.table_id};
                {/if}
                dt_{$subtbl.crud.table_id}.ajax.reload();
                {/foreach}
            }

        });
    {/if}
});

</script>


{foreach $subtables as $subtbl}
    {include file="crud/_js-crud-table.tpl" tbl=$subtbl.crud fsubtable='1' fkey=$subtbl.subtable_fkey_column flabel=$subtbl.label}
{/foreach}
