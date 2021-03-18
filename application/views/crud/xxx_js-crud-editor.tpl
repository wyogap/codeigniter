
<script type="text/javascript">
    var editor_{$crud.table_id} = null;

    $(document).ready(function() {

        editor_{$crud.table_id} = new $.fn.dataTable.Editor({
            ajax: "{$crud.ajax}",
            table: "#{$crud.table_id}",
            idSrc: "{$crud.key_column}",
            fields: [
                {foreach $crud.editor_columns as $col}
                {
                    label: "{$col.edit_label} {if $col.edit_label && $col.edit_compulsory}<span class='text-danger font-weight-bold'>*</span>{/if}",
                    name: "{$col.edit_field}",
                    {if $col.edit_type == 'js'}
                    type: 'hidden',
                    {else}
                    type: '{$col.edit_type}',
                    {/if}
                    {if isset($col.edit_options)}
                    options: [
                        {foreach from=$col.edit_options key=k item=v}
                        {if is_array($v)}
                            { label: "{$v.label}", value: "{$v.value}" },
                        {else}
                            { label: "{$v}", value: "{$k}" },
                            {/if}
                        {/foreach}
                    ],
                    {/if}
                    {if isset($col.edit_attr)}
                    attr: {
                        {foreach from=$col.edit_attr key=k item=v}
                        {$k} : "{$v}",
                        {/foreach}
                    },
                    {/if}
                    {if isset($col.edit_info)}
                    fieldInfo:  "{$col.edit_info}",
                    {/if}
                    {if $col.edit_type=='upload' || $col.edit_type=='image'}
                    display: function ( file_id ) {
                        if (!Number.isInteger(file_id)) {
                            return '<img src="'+file_id+'"/>';
                        }
                        
                        let files = null;
                        let file = null;
                        try {
                            files = editor.files('files');
                            file = files[file_id];
                        }
                        catch(err) {
                            //ignore
                        }

                        if (file !== null) {
                            return '<img src="'+editor.file( 'files', file_id ).thumbnail+'"/>';
                        }
                        else if (file_id != ""){
                            return '<img src="'+file_id+'"/>';
                        }
                        else {
                            return "";
                        }
                    },
                    clearText: "{__('Delete')}",
                    noImageText: "{__('No image')}",
                    uploadText: "{__('Choose file...')}",
                    noFileText: "{__('No file')}",
                    processingText: "{__('Uploading')}",
                    fileReadText: "{__('Reading file')}",
                    dragDropText: "{__('Drag and drop a file here to upload')}"
                    {/if}
                },
                {/foreach}
            ],
            i18n: {
                create: {
                    button: "{__('Add')}",
                    title: "{__('Add')} {$page_title}",
                    submit: "{__('Save')}"
                },
                edit: {
                    button: "{__('Edit')}",
                    title: "{__('Edit')} {$page_title}",
                    submit: "{__('Save')}"
                },
                remove: {
                    button: "{__('Delete')}",
                    title: "{__('Delete')} {$page_title}",
                    submit: "{__('Delete')}",
                    confirm: {
                        _: "{__('Do you want to delete')} %d {$page_title}?",
                        1: "{__('Do you want to delete')} 1 {$page_title}?"
                    }
                },
                error: {
                    system: "{__('System error. Please contact system administrator.')}"
                },
                datetime: {
                    previous: "{__('Previous')}",
                    next: "{__('Next')}",
                    months: [
                        "{__('January')}", 
                        "{__('February')}", 
                        "{__('March')}", 
                        "{__('April')}", 
                        "{__('May')}", 
                        "{__('June')}", 
                        "{__('July')}", 
                        "{__('Augustus')}",
                        "{__('September')}", 
                        "{__('October')}", 
                        "{__('November')}", 
                        "{__('December')}"
                    ],
                    weekdays: [
                        "{__('Mon')}", 
                        "{__('Tue')}", 
                        "{__('Wed')}", 
                        "{__('Thu')}", 
                        "{__('Wed')}", 
                        "{__('Fri')}", 
                        "{__('Sat')}"
                    ],
                    hour: "{__('Hour')}",
                    minute: "{__('Minute')}"
                }
            }
        });

        editor_{$crud.table_id}.on( 'open' , function ( e, type ) {
            {foreach $crud.editor_columns as $col}
                {if $col.edit_type == 'js' }
                editor_{$crud.table_id}.field("{$col.edit_field}").set(v_{$col.name});
                {/if}
            {/foreach}
        });

        editor_{$crud.table_id}.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;

                {foreach $crud.editor_columns as $col}
                    {if isset($col.edit_compulsory) && $col.edit_compulsory == true}
                    field = this.field('{$col.edit_field}');
                    if (!field.isMultiValue()) {
                        if (!field.val() || field.val() == 0) {
                            field.error('{__("Compulsory field")}');
                        }
                    }
                    {/if}
                {/foreach}

                /* If any error was reported, cancel the submission so it can be corrected */
                if (this.inError()) {
                    this.error('{__("Compulsory fields is not set")}');
                    return false;
                }
            }

            /* dont sent all data for remove */
            if (action === 'remove') {
                $.each(o.data, function (key, val) {
                    o.data[key] = {};
                    o.data[key].{$crud.key_column} = key;
                });
            }

            /* set the hidden js field */
            {foreach $crud.editor_columns as $col}
                {if $col["edit_type"] == "js"}
                $.each(o.data, function (key, val) {
                    o.data[key].{$col.edit_field} = v_{$col.name};
                });
                {/if}
            {/foreach}
            
        });        

        editor_{$crud.table_id}.on('postSubmit', function(e, json, data, action, xhr) {
            if (action=="upload") {

            }

        });

        {foreach $crud.columns as $col}
            {if isset($col.edit_event_js)}
            $(editor_{$crud.table_id}.field('{$col.edit_field}').node()).on('change', function() {
                let val = editor.field('{$col.edit_field}').val();
                {$col['edit_event_js']} (val, editor_{$crud.table_id});
            });
            {/if}
        {/foreach}

        /* Activate the bubble editor on click of a table cell */
        $('#{$crud.table_id}').on( 'click', 'tbody td.editable', function (e) {
            editor_{$crud.table_id}.bubble( this );
        } );

        /* Inline editing in responsive cell */
        $('#{$crud.table_id}').on( 'click', 'tbody ul.dtr-details li', function (e) {
            /* Ignore the Responsive control and checkbox columns */
            if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                return;
            }
    
            /* ignore read-only column */
            var colnum = $(this).attr( 'data-dt-column' );
            if ( colnum == 1 ) {
                return;
            }
        
            /* Edit the value, but this method allows clicking on label as well */
            editor_{$crud.table_id}.bubble( $('span.dtr-data', this) );
        });

        editor_{$crud.table_id}.on('open', function () {
            $('div.DTE_Footer').after( $('div.DTE_Body') );
        });

    });

</script>

