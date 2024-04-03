<style>
.adv-search-box {
    padding: 12px 10px 0px 10px;
    display: flex;
    flex-wrap: wrap;
}

.btn .caret {
    margin-left: 0;
}
.caret {
    display: inline-block;
    width: 0;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    border-top: 4px dashed;
    border-top: 4px solid\9;
    border-right: 4px solid transparent;
    border-left: 4px solid transparent;
}

.cust-input-grp .btn-group .adv-search-btn {
    box-shadow: none;
    border: 1px solid #ccc;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

</style>

{assign var=num_visible_filter value=0 }
{foreach $crud.filter_columns as $f} 
    {if $f.type != "js"}
        {assign var=num_visible_filter value=$num_visible_filter+1 }
    {/if}
{/foreach}

{if $num_visible_filter || $crud.search}
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        {if $crud.search}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group" id="adv-search"><input type="text" name="search" id="search"
                                        class="form-control" placeholder="{__('Pencarian')}">
                                    <div class="input-group-btn cust-input-grp">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary btn-search"><span
                                                    class="glyphicon glyphicon-search"><i class="fas fa-search"></i></span></button>
                                            {if $num_visible_filter}
                                            <a class="btn btn-default adv-search-btn"
                                                href="#"><span class='d-none d-md-inline'>{__('Filter')} </span><span class="caret"></span></a>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {if $num_visible_filter}
                        <div class="adv-search-box" style="display: none;">
                        {foreach $crud.filter_columns as $f} 
                            {if $f.type != 'js'}
                            <div class="form-group col-4 mb-0 mt-1 {$f.css}">
                                {if $f.type == 'select' || $f.type == 'tcg_select2'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_select" placeholder="{$f.label}">
                                        <option value=''>-- {$f.label} --</option>
                                        {if $f.invalid_value}
                                        <option value='null'>-- {__("Kosong/Data Tidak Valid")} --</option>
                                        {/if}
                                        {if isset($f.options)}
                                            {foreach from=$f.options key=k item=v}
                                            {if is_array($v)}
                                            {$v.label|trim}
                                            <option value="{$v.value}">{if empty($v.label)}-- {__("Kosong")} --{else}{$v.label}{/if}</option>
                                            {else}
                                            {$v|trim}
                                            <option value="{$k}">{if empty($v)}-- {__("Kosong")} --{else}{$v}{/if}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.type == 'distinct'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_distinct" placeholder="{$f.label}">
                                        <option value=''>-- {$f.label} --</option>
                                        {if isset($f.options)}
                                            {foreach from=$f.options key=k item=v}
                                            {if is_array($v)}
                                                {$v.value|trim}
                                                {if empty($v.value)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$v.value}">{$v.label}</option>
                                                {/if}
                                            {else}
                                                {$k|trim}
                                                {if empty($k)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$k}">{$v}</option>
                                                {/if}
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.type == 'date'}
                                    <input type="text" class="form-control filter_date" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.label}">
                                {else if $f.type == 'daterange'}
                                    <div class="input-daterange input-group filter_daterange" id="f_{$f.name}">
                                        <input type="text" class="input-sm form-control" name="{$f.name}_start" placeholder="{$f.label}"/>
                                        <span class="input-group-addon filter_daterange_separator">s/d</span>
                                        <input type="text" class="input-sm form-control" name="{$f.name}_end" />
                                    </div>                               
                                {else}
                                    <input class="form-control filter_{$f.type}" type="text" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.label}"/>
                                {/if}
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                        {/if}
                        {else if $crud.filter}
                        <div class="row">
                        {foreach $crud.filter_columns as $f} 
                            {if $f.type != 'js'}
                            <div class="form-group col-4 mb-0 mt-1 {$f.css}">
                                 {if $f.type == 'select' || $f.type == 'tcg_select2'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_select" placeholder="{$f.label}">
                                        <option value=''>-- {$f.label} --</option>
                                        {if $f.invalid_value}
                                        <option value='null'>-- {__("Kosong/Data Tidak Valid")} --</option>
                                        {/if}
                                        {if isset($f.options)}
                                            {foreach from=$f.options key=k item=v}
                                            {if is_array($v)}
                                            {$v.label|trim}
                                            <option value="{$v.value}">{if empty($v.label)}-- {__("Kosong")} --{else}{$v.label}{/if}</option>
                                            {else}
                                            {$v|trim}
                                            <option value="{$k}">{if empty($v)}-- {__("Kosong")} --{else}{$v}{/if}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.type == 'distinct'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_distinct" placeholder="{$f.label}">
                                        <option value=''>-- {$f.label} --</option>
                                        {if isset($f.options)}
                                            {foreach from=$f.options key=k item=v}
                                            {if is_array($v)}
                                                {$v.value|trim}
                                                {if empty($v.value)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$v.value}">{$v.label}</option>
                                                {/if}
                                            {else}
                                                {$k|trim}
                                                {if empty($k)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$k}">{$v}</option>
                                                {/if}
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.type == 'date'}
                                    <input type="text" class="form-control filter_date" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.label}">
                                {else if $f.type == 'daterange'}
                                    <div class="input-daterange input-group filter_daterange" id="f_{$f.name}">
                                        <input type="text" class="input-sm form-control" name="{$f.name}_start" placeholder="{$f.label} Awal"/>
                                        <span class="input-group-addon filter_datarange_separator" style="margin-top: 8px;">-</span>
                                        <input type="text" class="input-sm form-control" name="{$f.name}_end" placeholder="{$f.label} Akhir" />
                                    </div>                               
                                {else}
                                    <input class="form-control filter_{$f.type}" type="text" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.label}"/>
                                {/if}
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                        {/if}
                    </div>
                    {if $crud.filter && !$crud.search && $num_visible_filter>1 && !$crud.initial_load}
                    <div class="card-footer">
                        <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary btn-block" id='btn_crud_filter'
                                    name="button">{__('Tampilkan')}</button>
                        </div>
                        </div>
                    </div>
                    {/if}
                </div> <!-- end card-box-->
            </div> <!-- end col-->
        </div>
    </div>
</section>

{/if}