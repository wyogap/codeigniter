
<div class="crud-form" id="{$crud.table_id}_form" data-table-id="{$crud.table_id}" {if $detail}data-id="{$detail[$crud.key_column]}"{/if}>
    {foreach $crud.editor_columns as $col}
    <div class="form-group row {if $col.edit_css}{$col.edit_css}{/if}">
        {if $col.edit_type == 'readonly'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="{$col.edit_field}" name="{$col.edit_field}" {if $col.edit_compulsory}required="" data-validation="required"{/if} {if $detail}value="{$detail[$col.edit_field]}"{/if} readonly='' disabled=''>
            </div>
        {else if $col.edit_type == 'text'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="{$col.edit_field}" name="{$col.edit_field}" {if $col.edit_compulsory}required="" data-validation="required"{/if} {if $detail}value="{$detail[$col.edit_field]}"{/if}>
            </div>
        {else if $col.edit_type == 'textarea'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <textarea name="{$col.edit_field}" id="{$col.edit_field}" class="form-control"
                    style="" {if $col.edit_compulsory}required="" data-validation="required"{/if}>{if $detail}{$detail[$col.edit_field]}{/if}</textarea>
            </div>
        {else if $col.edit_type == 'date'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <input type="date" class="form-control" id="{$col.edit_field}" name="{$col.edit_field}" {if $col.edit_compulsory}required="" data-validation="required"{/if} {if $detail}value="{$detail[$col.edit_field]}"{/if}>
            </div>
        {else if $col.edit_type == 'datetime'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <input type="datetime" class="form-control" id="{$col.edit_field}" name="{$col.edit_field}" {if $col.edit_compulsory}required="" data-validation="required"{/if} {if $detail}value="{$detail[$col.edit_field]}"{/if}>
            </div>
        {else if $col.edit_type == 'upload'}
            <!-- TODO -->
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <div class="editor_upload noClear">
                    <div class="eu_table">
                        <div class="row">
                            <div class="cell upload limitHide">
                                <button class="btn btn-outline-secondary">Choose file...</button>
                                <input type="file" id="DTE_Field_picture" class="form-control">
                            </div>
                            <div class="cell clearValue"><button
                                    class="btn btn-outline-secondary">Clear</button></div>
                        </div>
                        <div class="row second">
                            <div class="cell limitHide">
                                <div class="drop"><span>Drag and drop a file here to upload</span></div>
                            </div>
                            <div class="cell">
                                <div class="rendered"><span>No file</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {else if $col.edit_type == 'select'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <select id="{$col.edit_field}" name="{$col.edit_field}" class="form-control"
                    placeholder="{__($col.edit_label)}" {if $col.edit_compulsory}required="" data-validation="required"{/if}>
                    <option value=''>-- {__($col.edit_label)} --</option>
                    {if isset($col.edit_options)}
                    {foreach from=$col.edit_options key=k item=v}
                    {if is_array($v)}
                    <option value="{$v.value}" {if $detail}{if $detail[$col.edit_field] == $v.value}selected{/if}{/if}>{$v.label}</option>
                    {else}
                    <option value="{$k}" {if $detail}{if $detail[$col.edit_field] == $k}selected{/if}{/if}>{$v}</option>
                    {/if}
                    {/foreach}
                    {/if}
                </select>
            </div>
        {else if $col.edit_type == 'select2' || $col.edit_type == 'tcg_select'}
            <label class="col-md-3 col-form-label" for="{$col.edit_field}">{__($col.edit_label)} {if $col.edit_compulsory}<span class="required">*</span>{/if} </label>
            <div class="col-md-9">
                <select id="{$col.edit_field}" name="{$col.edit_field}" class="form-control"
                    placeholder="{__($col.edit_label)}" {if $col.edit_compulsory}required="" data-validation="required"{/if}>
                    <option value=''>-- {__($col.edit_label)} --</option>
                </select>
            </div>
        {/if}
    </div>
    {/foreach}
</div>
